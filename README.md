# README #

### How do I get set up? ###

Place the files into mod/data/field/linkedradiobutton and run notifications.

Due to limitations in the database activity you will need to run the installhacks script which copies an image and language string into the core mod_data code. You can do this by typing:

php mod/data/field/linkedradiobutton/cli/installhacks.php

at the command line.

One the plugin is installed if you go to MOODLESITEROOT/mod/data/field/linkedradiobutton/migrate/migrate.php you will be able to choose radiobutton database fields that you wish to migrate over.
How do I get set up?
Place the files into blocks/databasetags and run notifications.
This plugin has a dependency on block_databasetags.
Once the plugin is installed you can add the block to any course/activity page, use the block configuration to pick the database activity and fields to include in the tag cloud.

block_databasetags, filter_databasetagcloud, datafield_linkedradiobutton and datafield_tag were created specifically for a resource database which can be seen at http://practicelearning.info/course/view.php?id=10. It was commissioned and funded by Focused on Learning, and coded and maintained by Andrew Hancox. We are certain this will be of use to others so we are contributing this development back in to the Moodle community.