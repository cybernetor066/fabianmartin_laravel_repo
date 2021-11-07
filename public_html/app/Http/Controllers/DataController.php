<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use ZipArchive;
use PDO;
use Illuminate\Support\Facades\DB;
use File;
use setasign\Fpdi\Fpdi;
use Illuminate\Filesystem\Filesystem;
use Imagick;
use ImagickPixel;

class DataController extends Controller
{
    

    //function to read the pdf structure of the core, update the database 
    function readPoolStructureCore(){


        $imagick = new Imagick();
        $imagick->readImage('mappenlager/Folienpool/Oberthema A/Thema A Childknot1.pdf');
        $imagick->writeImages('mappenlager/Folienpool/Oberthema A/Thema A Childknot1.jpg', false);


        
/* 
        //get status and timestamps of the spkStammdaten
        $spkDb = $this->getAllDataFromTable('spkstammdaten_tbl');

        //check the status and update changed blzs (planungsworkshop)
        $this->checkAndUpdateCorePresentations($spkDb,'Planungsworkshop','planungsworkshopchangedon');
        $this->checkAndUpdateCorePresentations($spkDb,'Planungsgespräch','planungsgespraechchangedon');
        $this->checkAndUpdateCorePresentations($spkDb,'IPG','investmentprozessgespraechchangedon');
        $this->checkAndUpdateCorePresentations($spkDb,'Strategiegespräch','strategiegespraechchangedon');  */

    }

    //function to read the folder and pdf structure, update the database and load the treeView with the updated structure
    public function readPoolStructure(){

    
        $directory = 'mappenlager/Folienpool/';

        $mainTopic = array();
        $subTopics = array();
        
        //loop through all maintknots
        foreach (scandir($directory) as $folder) {

            if ($folder !== '.' && $folder !== '..') {
                
                $mainTopics[] = array('name' => $folder, 'path' => $directory .''. $folder .'/','lastEdited'=>filemtime($directory .''. $folder .'/'),'new' => 'new','id' => 0);

            }
        }

        //loop through all dircetorys in the directory (childknots)
        foreach ($mainTopics as $folder) {

            //echo $folder['path'];

            //loop through all files in the directory
            foreach (scandir($folder['path']) as $file) {

                if ($file !== '.' && $file !== '..') {

                    //ignore files that are not pdfs
                    if (strpos($file, '.pdf')){

                        $subTopics[] = array('name' => $file, 'path' => $folder['path'] .''. $file,'mainTopic' =>$folder['name'],'lastEdited'=> filemtime($folder['path'] .''. $file ));

                    }
                }
            }

        }


        //get the mainKnots structure from the db
        $mainTopicsDb = $this->getAllDataFromTable('treeviewmainknots_tbl');
        //get the childKnots structure from the db
        $childTopicsDb = $this->getAllDataFromTable('treeviewchildknots_tbl');
        //Todo check for false return and write into an error log ? and response to FE

        //firt check if the db mainknots are still existent in Sharepoint (check if content was deleted)
        foreach ($mainTopicsDb as $mainTopicsDbInstance) {

            if ($this->checkArrayForValue($mainTopicsDbInstance->mainknotname,$mainTopics,'name')){

                //mainKnot still existent (no further action needed)
                
            }else{

                //mainKnot doesnt exists anymore (first delete childKnots)
                $this->deleteDbRowsByKey('treeviewchildknots_tbl',  $mainTopicsDbInstance->id,'mainknotid');
                //mainKnot doesnt exists anymore (mainKnot has to be deleted in DB)
                $this->deleteDbRowsByKey('treeviewmainknots_tbl',  $mainTopicsDbInstance->id,'id');
                //ToDo delete all (JPGs and single pdf pages (delete subfolder for that)) and all slidepool data corresponding to that mainKnot
                //Todo check for false return and write into an error log ? and response to FE

            } 

        }

        //update the db entry structure after the first crawl
        //get the mainKnots structure from the db
        $mainTopicsDb = $this->getAllDataFromTable('treeviewmainknots_tbl');
        //get the childKnots structure from the db
        $childTopicsDb = $this->getAllDataFromTable('treeviewchildknots_tbl');


        //second compare the sharepoint strucutre with the database structure, are there new mainknots?
        //loop through all mainKnots (from sharepoint)
        foreach ($mainTopics as $key => $mainTopicsInstance) {
            //loop through all mainKnots (from database)
            foreach ($mainTopicsDb as $mainTopicsDbInstance) {
                //are the mainknots already in the db (otherwise they are new by default)
                if ($mainTopicsInstance['name']== $mainTopicsDbInstance->mainknotname) {
                    
                    //have the mainknots been modified since the last synchronisation?
                    if ($mainTopicsInstance['lastEdited'] == $mainTopicsDbInstance->changedon) {
                        //they exist and were not modified (no further action needed) / they are flagged as old
                        $mainTopics[$key]['new'] = 'old';
                    }else{

                    
                        //set new parameter to update
                        $mainTopics[$key]['new'] = 'updated';
                        //they exist and were modified (an update of the db is needed)
                        $this->changeDateInTable('treeviewmainknots_tbl',$mainTopicsDbInstance->id,$mainTopicsInstance['lastEdited']);
                        $this->handleUpdatedChildKnots('treeviewchildknots_tbl',$subTopics,$childTopicsDb,$mainTopicsDbInstance->id,$mainTopicsDbInstance->mainknotname);
                        //Todo check for false return and write into an error log ? and response to FE
                        //toDo process (JPGs and single pdf pages (create subfolder for that))
                        
                    } 
                }
            }     
        }   

        //update the db entry structure after the second crawl
        //get the mainKnots structure from the db
        $mainTopicsDb = $this->getAllDataFromTable('treeviewmainknots_tbl');
        //get the childKnots structure from the db
        $childTopicsDb = $this->getAllDataFromTable('treeviewchildknots_tbl');


        //third loop through all processed sharepoint mainknots and see if there are completly new ones
        foreach ($mainTopics as $mainTopicsInstance) {

            //if the topic is new to the database
            if ($mainTopicsInstance['new'] == 'new') {
                
                //call function to insert the new mainknot into the db and retreive the new id
                $MainKnotId = $this->insertNewMainKnot('treeviewmainknots_tbl',$mainTopicsInstance);
                //call function to insert every childknot in the db that correspond to the new mainknot and generate folders with jpgs an single pdfs
                $this->handleNewChildKnots($mainTopicsInstance,$subTopics,$MainKnotId);
                //toDo process the childknots (JPGs and single pdf pages)
                //toDo check for false return and write error log and response to FE (for both function responses)

            }
        
        } 
    } 

    //function to create new subfolders for new childknots in order to store jpgs and pdfs
    function checkAndUpdateCorePresentations($spkDb,$presentationType,$parameter ){

        $corePath = 'mappenlager/' .$presentationType.'/';
        $spkCorePresenations = array();

    
        //loop through all presentations and save to array
        foreach (scandir($corePath) as $corePathInstance) {

            //only for pdf files
            if (strpos($corePathInstance, '.pdf')){
                    
                $spkCorePresenations[] = array('name' => $corePathInstance, 'path' => $corePath .''. $corePathInstance ,'lastEdited'=>filemtime($corePath .''. $corePathInstance ));
        
            }
        }


        //loop through db and array to check if there are new files 
        foreach ($spkCorePresenations as $spkCorePresenationInstance) {
            //loop through all mainKnots (from database)
            foreach ($spkDb as $spkDbInstance) {

        
                //check for matching (blz db and blz_plw in datacointainer)
                if ($spkCorePresenationInstance['name']== $spkDbInstance->spkblz . '_' . $presentationType .'.pdf') {

                    

                    if ($spkCorePresenationInstance['lastEdited']== $spkDbInstance->$parameter) {
                    
                        //corePresentation up to date

                    }else{

                    //corePresentation hast to be updated
                    //first delete folder if it exists
                
                    $this->deleteSubFolderChild($spkCorePresenationInstance['path']);
                    //second rewrite the folder
                    $this->createNewSubFolderChild($spkCorePresenationInstance['path']);
                    //third generate single pdf pages
                    $this->createSinglePdfsChildKnot($spkCorePresenationInstance);
                    //fourth update the timestamp in the db
                    $this->changeDateInTableSpk('spkstammdaten_tbl',$spkDbInstance->id,$spkCorePresenationInstance['lastEdited'],$parameter);

                    }




                }

            }

        }





    }

    //function to handle updated childKnots (update to db, delte & make folder, create single pdfs and jpgs)
    function handleUpdatedChildKnots($tblName,$subTopics,$childTopicsDb,$mainKnotId,$mainKnotname){

        //first delete all childknot db entries of the updated mainknot 
        $this->deleteDbRowsByKey('treeviewchildknots_tbl',$mainKnotId,'mainknotid');
        //ToDo delete all folder (JPGs and single pdf pages (delete subfolder for that)) and all slidepool data corresponding to that mainKnot
        //Todo check for false return and write into an error log ? and response to FE


        

        //second delete all childknot db folder and recrawl them
        foreach ($subTopics as $subTopicsInstance) {

            if($subTopicsInstance['mainTopic']==$mainKnotname){

                $this->deleteSubFolderChild($subTopicsInstance['path']);
                //rewrite childknot in db 
                $this->insertNewChildKnot('treeviewchildknots_tbl',$subTopicsInstance,$mainKnotId);
                //create new subfolder
                $this->createNewSubFolderChild($subTopicsInstance['path']);
                //create single pdfs per page into the new subfolder (in order to build the blueprint later)
                $this->createSinglePdfsChildKnot($subTopicsInstance);

            }
        }



    }


    //function to handle new childKnots (save to db, make folder, create single pdfs and jpgs)
    function handleNewChildKnots($mainTopicsInstance,$subTopics,$MainKnotId) {

        foreach ($subTopics as $subTopicsInstance) {

            if ($subTopicsInstance['mainTopic']== $mainTopicsInstance['name']) {

                //insert childknot in db
                $this->insertNewChildKnot('treeviewchildknots_tbl',$subTopicsInstance,$MainKnotId);
                //create new subfolder
                $this->createNewSubFolderChild($subTopicsInstance['path']);
                //create single pdfs per page into the new subfolder (in order to build the blueprint later)
                $this->createSinglePdfsChildKnot($subTopicsInstance);
                //toDo handle repsonse with error log
            }

        }

    }

    //function to create new subfolders for new childknots in order to store jpgs and pdfs
    function deleteSubFolderChild($folderpath){


        //delete file extension from path
        $folderpathStrapped = substr($folderpath, 0 , (strrpos($folderpath, ".")));
        $folderpathStrapped = $folderpathStrapped  .'/';

        try{

            // folder does not exist -> create folder
            File::deleteDirectory($folderpathStrapped);
            

            return true;

        }catch(Exception $e){

        
            return $e->get_messsage();

        }     

        



    }

    //function to delete  subfolders for new childknots in order to store jpgs and pdfs
    function createNewSubFolderChild($folderpath){


        //delete file extension from path
        $folderpathStrapped = substr($folderpath, 0 , (strrpos($folderpath, ".")));
        $folderpathStrapped = $folderpathStrapped  .'/';

        if (!file_exists($folderpathStrapped)) {

            try{
            // folder does not exist -> create folder
            File::makeDirectory($folderpathStrapped, 0777, true, true);

            return true;

            }catch(Exception $e){

        
                return $e->get_messsage();

            }     

        }



    }

    //function to create insert single pdfs into the subfolders for new childknots
    function createSinglePdfsChildKnot($subTopicsInstance){

        //delete file extension from path
        $folderpath = substr($subTopicsInstance['path'], 0 , (strrpos( $subTopicsInstance['path'], ".")));
        $folderpath = $folderpath .'/';
        //get the file 
        $file =$subTopicsInstance['path'];

        $pdf = new FPDI();
        $pdf->SetAutoPageBreak('auto',0);
        $pageCount = $pdf->setSourceFile($file);


        for ($i = 1; $i < $pageCount+1; $i++) {

            $pdf = new FPDI();
            $pdf->SetAutoPageBreak('auto',0);
            $pageCount = $pdf->setSourceFile($file);
            $tpl = $pdf->importPage($i , '/MediaBox');
            $pdf->addPage('L');
            $pdf->useTemplate($tpl, null, null,null ,null , true);
            //Korrigierte Seitenzahlen übertragen (evtl. löschen falls es bei der Rückführung in pptx probleme gibt)
            $pdf->SetFont('Courier', '', 10);
            $pdf->setFillColor(255,255,255); 
            $pdf->SetY(178);
            $pdf->SetX(315);
            $pdf->Cell(30, 10, $pdf->PageNo(), 200, 200, 'e', true);
            $pdf->Output('F',$folderpath .$i.'.pdf');
            

        } 


    }

    function createSinglePngChildknot(){

        $image = new Imagick();
        $image->newImage(1, 1, new ImagickPixel('#ffffff'));
        $image->setImageFormat('png');
        $pngData = $image->getImagesBlob();
        echo strpos($pngData, "\x89PNG\r\n\x1a\n") === 0 ? 'Ok' : 'Failed'; 

    }

    //function to insert a newchildKnot with all parameters
    public function insertNewChildKnot($tblName,$subTopicsInstance,$MainKnotId){
        
        //toDo number of pages is still a dummy
        try{

            DB::table($tblName)

                ->insert([
                'mainknotid' => $MainKnotId,
                'childknotname' => $subTopicsInstance["name"],
                'childknotpath' => $subTopicsInstance["path"],
                'numberofpages' => 12,
                'changedon'=>$subTopicsInstance["lastEdited"]

            ]); 

            return true;

            }catch(Exception $e){

                return $e->get_messsage();;

            }     

                

            
    }

    //function to insert a newchildKnot with all parameters
    public function insertNewDownloadTracking($tblName,$documentType,$vertriebsRegion,$month,$year){
        
        //toDo number of pages is still a dummy
        try{

            DB::table($tblName)

                ->insert([
                'downloadtyp' => $documentType,
                'spkvertriebsregion' => $vertriebsRegion,
                'month' => $month,
                'year' => $year,
            

            ]); 

            return true;

            }catch(Exception $e){

                return $e->get_messsage();;

            }     

                

            
    }

    //function to search for a value in a multidimensional array (returns true if found and false if its misisng)
    function checkArrayForValue($id, $array,$parameter) {

        foreach ($array as $arrayInstance) {

                if ($arrayInstance[$parameter] === $id) {
                    return true;
                }
            }
        return false;
    }

    //function to delete rows by key out of postgres db
    public function deleteDbRowsByKey($tblName,$mainKnotId,$parameterName){
        
        try{
        
            DB::table($tblName)->where($parameterName, $mainKnotId)->delete(); 

            return true;

        }catch(Exception $e){

            return $e->get_messsage();;

        }     

            

        
    }

    //function to insert a new mainKnot with all parameters
    public function insertNewMainKnot($tblName,$mainTopicsInstance){
        
        try{

            $Id = DB::table($tblName)

                ->insertGetId([
                'mainknotname' => $mainTopicsInstance["name"],
                'mainknotpath' => $mainTopicsInstance["path"],
                'vertriebsgespraech' => false,
                'strategiegespraech' => false,
                'planungsworkshop' => false,
                'planungsgespraech' => false,
                'investmentprozessgespraech' => false,
                'folienpool'=> false,
                'changedon'=>$mainTopicsInstance["lastEdited"]

            ]); 

            return $Id;

        }catch(Exception $e){

            return $e->get_messsage();;

        }     

            

        
    }

    //function to update a certain knot with the new change date
    public function changeDateInTable($tblName,$id,$timestamp){


        try{

            DB::table($tblName)
            ->where('id', $id)
            ->update(['changedon' => $timestamp ]);
            return true;

        }catch(Exception $e){

            return $e->get_messsage();;
        }     

    }

    //function to update a certain knot with the new change date
    public function changeDateInTableSpk($tblName,$id,$timestamp,$parameter){


        try{

            DB::table($tblName)
            ->where('id', $id)
            ->update([$parameter=> $timestamp ]);
            return true;

        }catch(Exception $e){

            return $e->get_messsage();;
        }     

    }

    //function to get all data from a tbl out of postgres db
    public function getAllDataFromTable($tblName){

        try{

            return DB::table($tblName)->get();

        }catch(Exception $e){

            return $e->get_messsage();

        }


    }

    //function to get all Sparkassenstammdaten out of the postgres DB (with facades DB shema) and load the view donwloadVd with dynamic data
    public function loadVertriebsunterstuetzung(){

        //db query load all spkStammdaten
        $spk = DB::table('spkstammdaten_tbl')->get();

        return view('downloadVd')->with(array('spk'=>$spk));


    }

    //function to get all Sparkassenstammdaten out of the postgres DB (with facades DB shema)
    public function getAllSpkStammdaten(){


        try{


            $spk = DB::table('spkstammdaten_tbl')->get();

            foreach ($spk as $spkInstance) {
                

                echo   "<input type='checkbox' name='spkSelect[]' value='. $spkInstance->spkblz.'>  $spkInstance->spkblz   $spkInstance->spkname. <br>";
                

            } 


        }catch(Exception $e){
            

                echo $e->get_messsage();

        }


    }

    //function to search certain Sparkassenstammdaten out of the postgres DB (with facades DB shema) by BLZ or name
    public function searchDbSpkStammdaten(Request $request){

        $searchParameter = $request->get('searchParameter');
        

        try{

            $spk = DB::table('spkstammdaten_tbl')
                    ->where('spkname', 'like', '%'.$searchParameter.'%')
                    ->orWhere('spkblz', $searchParameter)
                    ->get();

                    return view('downloadVd')->with(array('spk'=>$spk));



            }catch(Exception $e){
            

            echo $e->get_messsage();

            } 

        
    }

    //old function to use PDO shema
    public function testDbConnectionArchive(){


        try{

        $myPDO = new PDO("pgsql:host=localhost,dbname=postgres","postgres","hanshans");
        $query = $myPDO->prepare('SELECT * FROM spkstammdaten');
        $query->execute();

        while ($row = $query->fetch(PDO::FETCH_ASSOC))
        {
            $spkName = $row['spkname'];
            $spkBlz =$row['spkblz'];
            echo $spkBlz;
            echo '   ';
            echo $spkName;
            echo "<br>";
        }

        echo "<br>";
        echo "database connected... all data fetched from spkstammdaten";

        }catch(PDOException $e){
            

            echo $e->get_messsage();

        }





    }

    //function to download VB Documents (no BLZ multiselect)
    public function downloadSinglePdfFunction(Request $request){
        
        header("Content-type: application/zip");


        $zip = new ZipArchive;

        if (file_exists('mappenlager/Vertriebsfolien/Download.zip')) {

            unlink('mappenlager/Vertriebsfolien/Download.zip');
        
            }  
        
        
        
        if ($zip->open('mappenlager/Vertriebsfolien/Download.zip', ZipArchive::CREATE) === TRUE){
            

                $documentSelect = $request->get('documentSelect');



                foreach ($documentSelect as $documentSelected){ 

                

                    if (file_exists($documentSelected)) {

                
                        $zip->addFile($documentSelected,basename($documentSelected));
                    } 
                }

                $zip->close();
                if(ob_get_length() > 0) {
                    ob_clean();
                }
                $zipDownload = 'mappenlager/Vertriebsfolien/Download.zip';

                header("Content-disposition: attachment; filename=\"" . basename($zipDownload) . "\""); 
                readfile($zipDownload);  
        }

    }

    function checkDocumentType($path){

        if (strpos($path, 'DVK Foliensatz') !== false) {

            return 'DVK_Folienpool';

        }elseif(strpos($path, 'SDD Foliensatz') !== false) {

            return 'SDD_Folienpool';

        }elseif(strpos($path, 'Planungsworkshop_Maßnahmen') !== false) {

            return 'Planungsworkshop_Maßnahmen'; 

        }elseif(strpos($path, 'DekaStruktur_Auswertung') !== false) {

            return 'DekaStruktur'; 

        }elseif(strpos($path, 'AGB Änderungsmechanismus FVV_Rückmeldungen') !== false) {

            return 'AGB Änderungsmechanismus FVV_Rückmeldungen'; 

        }elseif(strpos($path, 'AGB Änderungsmechanismus FVV_Foliensatz') !== false) {

            return 'AGB Änderungsmechanismus FVV_Foliensatz'; 

        }elseif(strpos($path, 'VEV Musteranschreiben') !== false) {

            return 'VEV Musteranschreiben'; 


        }elseif(strpos($path, 'VEV Report_Avis') !== false) {

            return 'VEV Report_Avis'; 

        }


        


    }

    //function to download VD , VB & PB Documents ( BLZ multiselect)
    public function downloadSpkDocuments(Request $request){

        header("Content-type: application/zip");

        $zip = new ZipArchive;

        if (file_exists('mappenlager/Vertriebsunterstützung/Download.zip')) {

            unlink('mappenlager/Vertriebsunterstützung/Download.zip');
        
        }
        
        
        
        if ($zip->open('mappenlager/Vertriebsunterstützung/Download.zip', ZipArchive::CREATE) === TRUE)


        $spkSelect = $request->get('spkSelect');
        $documentSelect = $request->get('documentSelect');

        //array to store all available document 
        $matches = array();
        $month = date("F", strtotime('m'));
        $year = date("Y");
        foreach ($documentSelect as $documentSelected){ 

            foreach ($spkSelect as $spkBlz){ 


                if ($documentSelected !="mappenlager/Vertriebsunterstützung/Planungsworkshop_Maßnahmen/" && $documentSelected !="mappenlager/Vertriebsunterstützung/VEV Musteranschreiben/"){

                    //save in array to add it into Zip File later
                    $matches[] = glob($documentSelected . '*' . $spkBlz. '*');
            
                    //check if file exists for tracking
                    if (count(glob($documentSelected . '*' . $spkBlz. '*'))>0){

                        //get documenttype from path
                        $documentType = $this->checkDocumentType($documentSelected);
                        //get Vertriebsregion from db (over blz match)
                        $vertriebsRegion = DB::table('spkstammdaten_tbl')->where('spkblz', $spkBlz)->value('spkregion');
                        $this->insertNewDownloadTracking('trackingdownload_tbl',$documentType,$vertriebsRegion,$month,$year);

                        
                    }else{

                        

                    }


                }elseif($documentSelected == "mappenlager/Vertriebsunterstützung/Planungsworkshop_Maßnahmen/"){

                    $matches[] = glob($documentSelected . 'Planungsworkshop_Maßnahmen.pptx');


                }elseif($documentSelected =="mappenlager/Vertriebsunterstützung/VEV Musteranschreiben/"){


                    $matches[] = glob($documentSelected . 'Musteranschreiben_Spk_VEV_2020.pdf');

            
                }

            }

        }


        //check if there have been documents selected, which are the same for every region
        if (in_array("mappenlager/Vertriebsunterstützung/Planungsworkshop_Maßnahmen/", $documentSelect)) {
    
            $this->insertNewDownloadTracking('trackingdownload_tbl','Planungsworkshop_Maßnahmen','none',$month,$year);

        }
        
        if(in_array("mappenlager/Vertriebsunterstützung/VEV Musteranschreiben/", $documentSelect)){

            $this->insertNewDownloadTracking('trackingdownload_tbl','VEV Musteranschreiben','none',$month,$year);

        }


        $countMatches =  count($matches);

        for ($i = 0; $i < $countMatches; ++$i) {



            if (isset($matches[$i ][0])) {
                

                    $zip->addFile($matches[$i ][0],basename($matches[$i ][0]));
                
            }else{

                //ToDo how to report missing files to the user ? Some files are only once with alle BLZs !

            }
            
        }

        // All files are added, so close the zip file.
        $zip->close();
        if(ob_get_length() > 0) {
            ob_clean();
        }
        $zipDownload = 'mappenlager/Vertriebsunterstützung/Download.zip';

        header("Content-disposition: attachment; filename=\"" . basename($zipDownload) . "\""); 
        readfile($zipDownload);   

    }





}
