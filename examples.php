<?php

include "vendor/autoload.php";

use Tsc\CatStorageSystem\FileSystem;

# Create a set of files
try {
    create_file_group("very_cute\\");
} catch (Exception $e) {
    $e->getMessage();
}

# Rename the default set of files
//try {
//    rename_file_group("very_cute\\");
//} catch (Exception $e) {
//    $e->getMessage();
//}

# Delete the whole file group
delete_file_group("very_cute\\");

# Update the size and modification time of the file group
//update_file_group("very_cute\\");

# Get all information about this new directory (size, number of files etc.)
//$dir = instantiate_dir("cat_storage", "\\very_cute");
//$fileSystem = new FileSystem();
//get_directory_information($dir, $fileSystem);

//# Rename the directory
//try {
//    $fileSystem->renameDirectory($dir, "\\super_cute\\");
//} catch (Exception $e) {
//    $e->getMessage();
//}