<?php


$dir_path = "images/";
$extensions_array = array('jpg', 'png','jpeg','gif','tif','pdf','psd');

//Create Thumbnail images of all the files and copy the original files to the default folders
function thumbnailImage ($dir_path, $file){
//Add directories unless they already exist
    if (!is_dir($dir_path.'default/')) {
        mkdir($dir_path.'default/',0600);
    }
    if (!is_dir($dir_path.'thumbnail/')) {
        mkdir($dir_path.'thumbnail/',0600);
    }

    $thumb=$dir_path.'/thumbnail/'; 
    $default=$dir_path.'/default/'; 
    
    $extension = strtolower(pathinfo($dir_path.$file,PATHINFO_EXTENSION));
    $image = strtolower(pathinfo($dir_path.$file,PATHINFO_BASENAME));
    $DisplayName = strtolower(pathinfo($dir_path.$file,PATHINFO_FILENAME));
    
    //if the file has not already been made then make a thumbnail
    //echo(is_dir($thumb.$file));
    if(!file_exists($thumb.$file)){

    $thumbWidth = 600;
    $thumbHeight = 450;
    
    
    list($defaultWidth, $defaultHeight) = getimagesize($dir_path.$file);
    $scale_ratio = $defaultWidth / $defaultHeight;
    
    if (($thumbWidth / $thumbHeight) > $scale_ratio) {
           $thumbWidth = $thumbHeight * $scale_ratio;
    } else {
           $thumbHeight = $thumbWidth / $scale_ratio;
    }

    //echo("This file is ".$image." with the extension (".$extension.')<br>');

    
    if ($extension == "gif"){ 
      $NewImage = imagecreatefromgif($dir_path.$file);
    } else if($extension =="png"){ 
      $NewImage = imagecreatefrompng($dir_path.$file);
    } else if($extension =="jpg" or $extension =="jpeg"){ 
      $NewImage = imagecreatefromjpeg($dir_path.$file);
    }     
    else if($extension =="tif" or $extension =="tiff" ){ 
      echo "this file is a tiff so the file cannot be altered or displayed<br><br>";

    }
    if(isset($NewImage)){
    $placeholder = imagecreatetruecolor($thumbWidth,$thumbHeight);
    imagecopyresampled($placeholder, $NewImage,0,0,0,0,$thumbWidth,$thumbHeight,$defaultWidth,$defaultHeight);
    imagejpeg($placeholder, $thumb.$image,75);
    imagejpeg($NewImage, $default.$image,100);
        
        
    //echo '<div class="container"><img class="image" src="'.$thumb.$image.'" alt="'.$image.'"><div class="middle"><div class="text">'.$image.'</div></div>'."&nbsp;&nbsp;";
            echo '<div class="container" ><img class="image" onclick="openDefaultImage(\''.$default.$image.'\',\''.$DisplayName.'\')" src="'.$thumb.$image.'" alt="'.$image.'"><div class="middle" onclick="openDefaultImage(\''.$default.$image.'\',\''.$DisplayName.'\')"><div class="text" onclick="openDefaultImage(\''.$default.$image.'\',\''.$DisplayName.'\')">'.$DisplayName.'</div></div></div>'."&nbsp;&nbsp;";  
    //echo '<img src="'.$default.$image.'" alt="random image">'."&nbsp;&nbsp;<br>".$default.$image.'<br>';
    }
    
    } else {
        $conditions = (string)$default.$image;
            echo '<div class="container" ><img class="image" onclick="openDefaultImage(\''.$default.$image.'\',\''.$DisplayName.'\')" src="'.$thumb.$image.'" alt="'.$image.'"><div class="middle" onclick="openDefaultImage(\''.$default.$image.'\',\''.$DisplayName.'\')"><div class="text" onclick="openDefaultImage(\''.$default.$image.'\',\''.$DisplayName.'\')">'.$DisplayName.'</div></div></div>';  
    
    }
    
}


function FindImages($dir_path,$extensions_array) {
if(is_dir($dir_path)){
    $files = scandir($dir_path);
}
        /*echo ("This is the array of the new folder: ");
        echo implode(" ",$files);
        echo ("<br><br>");*/
    for($i = 0; $i < count($files); $i++)
    {
        $file = $files[$i];
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        if ($file!= '..' && $file!= '.'){

        //echo ('The File is: '.$file.'<br>');
        if ($extension == null and isset($file))
        {
            //If the file is a folder, go into the folder and recur
            $sub_dir_path = $dir_path.$file.'/';
            //echo ('The file path is: '.$sub_dir_path.'   -  ');
            //echo ('This is a folder <br>');
            
            
            if(substr($sub_dir_path, -8) === "default/" || substr($sub_dir_path, -10) === "thumbnail/" )
            {//echo("This is the default/thumbnail folder so we don't look inside<br><br>");
            }
            else {
                echo('<div class="FolderHeader"><br><h1>'.$file.'</h1><br></div>');
                FindImages($sub_dir_path,$extensions_array);
            }
        }
        else
        {
            //Is the file an image?
            if(in_array($extension , $extensions_array))
            {
            //echo("This is an image<br>".$dir_path.$file.'<br>');
            thumbnailImage($dir_path,$file);
            }
        }
    }}
}

FindImages($dir_path,$extensions_array);

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    

    
.container {
    position: relative;
    display: inline;
    float:left;
    width:25%;
    height:25%;
  text-align: center;
}
.FolderHeader {

    position: relative;
    width: 25%;
    display: inline;
    float:left;
    height:100%;
  text-align: center;
}

.image {
    height: 100%;
    width:100%;
  opacity: 1;
  display: inline;
  transition: .5s ease;
  backface-visibility: hidden;
}

.middle {
    margin:0;
  transition: .5s ease;
  opacity: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -100%);
  text-align: center;
}

.container:hover .image {
  opacity: 0.3;
}

.container:hover .middle {
  opacity: 1;
}

.text {
  background-color: #4CAF50;
  color: white;
  font-size: 16px;
  padding: 16px 32px;
}
    
body {font-family: Arial, Helvetica, sans-serif;}

#myImg {
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 900px;
}

/* Caption of Modal Image */
#caption {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    text-align: center;
    color: #ccc;
    padding: 10px 0;
    height: 150px;
}

/* Add Animation */
.modal-content, #caption {    

    animation-name: zoom;
    animation-duration: 0.6s;
}




@keyframes zoom {
    from {transform:scale(0)} 
    to {transform:scale(1)}
}

/* The Close Button */
.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

    .md-show.md-effect-5 ~ .md-overlay {
	background: rgba(0,127,108,0.8);
}

.md-effect-5 .md-content {
	transform: scale(0) rotate(720deg);
	opacity: 0;
	transition: all 0.5s;
}

.md-show.md-effect-5 .md-content {
	transform: scale(1) rotate(0deg);
	opacity: 1;
}

} 
    
    
    
    
    
    
    
    
    
    
    
    
</style>
</head>
<body>
<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="ModelImage">
  <div id="caption"></div>
</div>
    
    <script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the image and insert it inside the modal - use its "alt" text as a caption
function openDefaultImage ($image, $caption){ 
    

var img = document.getElementById($image);
var modalImg = document.getElementById("ModelImage");
var captionText = document.getElementById("caption");
    modal.style.display = "block";
    captionText.innerHTML = $caption;
    modalImg.src = $image;
    /*
    function(){


}

// Get the <span> element that closes the modal*/
var span = document.getElementsByClassName("close")[0];
// When the user clicks on <span> (x), close the modal
span.onclick = function() {modal.style.display = "none";}
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
}

</script>
    
    
    <p id="here"></p>
    </body>
</html>
    
    
    
    
    
    
    
    
    
    
    