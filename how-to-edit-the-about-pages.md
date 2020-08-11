# Editing "about page" content using the GitHub web interface

All of the content presented on the about pages is created using code blocks in the `ArticleController` https://github.com/microsimulation/ijm/blob/master/journal/src/Controller/AboutController.php

This controller passes the text as objects to the view renderer, and each object represents how that piece of content should be displayed, for example a `Paragraph`, `Listing` and `ArticleSection`.

Adding new objects requies a little knowledge of how PHP syntax works but copying and pasting existing content should help.

Editing existing content is a simpler task by comparison as you should only need to edit the text within existing objects.

## The `ArticleController` Layout
Each part of the about page is rendered by a separate `Action` within this controller named to correspond with the page section it contains. For example, the Editorial Board page http://microsimulation.pub/about/editorial-board is rendered by the function named `boardAction`. Scrolling down the code file you should see it is well structured with actions named for each of the pages you may want to edit.

## Editing text in existing objects
Once you have located the content you wish to edit you can change the text that is rendered by editing the text in the appropriate `string` represented between two single quotations marks `' '`.

For example, if you wanted to change the opening paragraph on the Aims and Scope page then you would edit the following line to change the text within the single quotation marks:

`            new Paragraph('The International Journal of Microsimulation (IJM) is the official online peer-reviewed journal of the International Microsimulation Association (<a href="https://microsimulation.org/storage/ijm_flyer.pdf">see flyer</a>).'),`

# Committing change
You can edit a file directly on GitHub by finding it in the file list (or via a link like the one above to the `AboutController`) and clicking the pencil icon / edit button.

Below the editor you will find a "Commit changes" dialog box. The instructions are in the boxes themeslves, so add a short "commit message" in the first, for example "Update first paragraph of the aims and scope page" so that others can see what your changes do to the page. You can add an optional description if you feel more information is required but these are not required.

Ensure the correct email address is selected so that the commit is linked to your account.

Choose "Commit directly to the `master` branch" as this will change the code directly in the repository and alert the web master or dev team.

Finally, click the "Commit changes" button and the web master or dev team will be notified. They will then need to check and deploy these changes.

You can optionally choose to create a new branch and start a pull request if you are not sure about your changes as this will allow the web master and dev team to review them before commit directly to the main codebase. More information on pull requests can be found here https://docs.github.com/articles/using-pull-requests
