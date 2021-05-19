<?php

include "vendor/autoload.php";

use Tsc\CatStorageSystem\FileSystem;

# Create a set of files
create_file_group("very_cute\\");

# Rename the default set of files
//rename_file_group("cute\\");

# Delete the whole file group
//delete_file_group("cute\\");

# Update the size and modification time of the file group
update_file_group("cute\\");

# Get all information about this new directory (size, number of files etc.)
$dir = instantiate_dir("..\\cat_storage\\", "");
$fileSystem = new FileSystem();
get_directory_information($dir, $fileSystem);

# Rename the directory
$dir->setName("cute\\");
$fileSystem->renameDirectory($dir, "\\super_cute\\");