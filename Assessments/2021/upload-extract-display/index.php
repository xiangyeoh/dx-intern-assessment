<!--
Candidate name : Yeoh Kai Xiang
Remarks : After trying for several times, only compressed file of type "WinRAR ZIP Archive"
          (created with archive option: Create solid archive) is suitable to be used in this 
          website because PHP had provided an object, which is ZipArchive which does not 
          accept file with type "WinRAR archive".
-->

<?php
if (isset($_POST["btn_zip"])) {

  //---------------------------------------------------------------------------------------
  //This code section is used to remove all the previous images stored in the upload folder
  $files = scandir('upload/');
  foreach ($files as $file) {
    if (strpos($file, 'jpg') !== false || strpos($file, 'png')) {
      unlink('upload/' . $file);
    }
  }
  //----------------------------------------------------------------------------------------

  $output = '';

  //check if files uploaded exists
  if ($_FILES['zip_file']['name'] != '') {
    $file_name = $_FILES['zip_file']['name'];

    //seperate file name and its extension
    $array = explode(".", $file_name);
    $name = $array[0];
    $ext = $array[1];

    //perform operation if file extension is zip
    if ($ext == 'zip') {
      $path = 'upload/';
      $location = $path . $file_name;

      //move the uploaded zip file into an empty folder pre-created
      if (move_uploaded_file($_FILES['zip_file']['tmp_name'], $location)) {
        $zip = new ZipArchive;

        //open the zip file and if success, extract it to the empty folder
        if ($zip->open($location)) {
          $zip->extractTo($path);
          $zip->close();
        }

        //scan through the folder where the extracted images located in
        $files = scandir($path);

        //loop through all the images and if the types are jpg or png, append them to the output
        foreach ($files as $file) {
          if (strpos($file, 'jpg') !== false || strpos($file, 'png')) {
            $output .= '<div class="col-md-6"><div style="padding:16px; border:1px solid #CCC;"><img src="upload/' . $file . '" width="170" height="240" /></div></div>';
          }
        }
        //remove the zip file
        unlink($location);
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Engineering Internship Assessment</title>
  <meta name="description" content="The HTML5 Herald" />
  <meta name="author" content="Digi-X Internship Committee" />

  <link rel="stylesheet" href="style.css?v=1.0" />
  <link rel="stylesheet" href="custom.css?v=1.0" />

  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js" integrity="sha512-VQQXLthlZQO00P+uEu4mJ4G4OAgqTtKG1hri56kQY1DtdLeIqhKUp9W/lllDDu3uN3SnUNawpW7lBda8+dSi7w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>
    $(function() {
      //set the attribute of the upload button to false
      $('#btn_submit').attr("disabled", true);

      //if the input has changed, perform operation
      $('#input').on("change", () => {

        var input = $('#input').val();

        //if input has value and only when the file uploaded is in .zip or .rar format, perform operation
        if (input !== "" && (input.indexOf("rar") >= 0 || input.indexOf("zip") >= 0)) {
          $('#btn_submit').attr("disabled", false);
        } else {
          $('#btn_submit').attr("disabled", true);
        }
      });
    })
  </script>
</head>

<body>
  <div class="top-wrapper">
    <img src="https://assets.website-files.com/5cd4f29af95bc7d8af794e0e/5cfe060171000aa66754447a_n-digi-x-logo-white-yellow-standard.svg" alt="digi-x logo" height="70" />
    <h1>Engineering Internship Assessment</h1>
  </div>

  <div class="instruction-wrapper">
    <h2>What you need to do?</h2>
    <h3 style="margin-top: 31px">
      Using this HTML template, create a page that can:
    </h3>
    <ol>
      <li>
        <b class="yellow">Upload</b> a zip file - containing 5 images (Cats,
        or Dogs, or even Pokemons)
      </li>
      <li>
        after uploading, <b class="yellow">Extract</b> the zip to get the
        images
      </li>
      <li><b class="yellow">Display</b> the images on this page</li>
    </ol>

    <h2 style="margin-top: 51px">The rules?</h2>
    <ol>
      <li>
        May use <b class="yellow">any programming language/script</b>. The
        simplest the better *wink*
      </li>
      <li><b class="yellow">Best if this project could be hosted</b></li>
      <li>
        <b class="yellow">If you are not hosting</b>, please provide a video
        as proof (GDrive video link is ok)
      </li>
      <li>
        <b class="yellow">Submit your code</b> by pushing to your own github
        account, and share the link with us
      </li>
    </ol>
  </div>

  <!-- DO NO REMOVE CODE STARTING HERE -->
  <div class="display-wrapper">
    <h2 style="margin-top: 51px">My images</h2>
    <div class="append-images-here">

      <!-- THE IMAGES SHOULD BE DISPLAYED INSIDE HERE -->
      <?php
      if (isset($output)) {
        echo $output;
      } else {
      ?>
        <p>No image found. Your extracted images should be here.</p>
      <?php
      }
      ?>
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <input type="file" name="zip_file" id="input">
        <input type="submit" value="Upload" name="btn_zip" id="btn_submit">
      </form>
    </div>
  </div>
  <!-- DO NO REMOVE CODE UNTIL HERE -->
</body>

</html>