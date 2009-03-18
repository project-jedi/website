/* 
* $Id: Readme.txt v 1.0 12 July 2003 Catwolf Exp $
* Module: WF-FAQ
* Version: v1.00
* Release Date: 12 July 2003
* Author: Catzwolf
* Licence: GNU
*/

-------------
Introduction:
-------------

Hi,

Thanks for taking the time to download and try QF-FAQ, I hope that you enjoy using this module and you find a use for it 

Somewhere within your website.

WF-FAQ is an Answer and question module (FAQ) where you can give your users information to frequently asked questions all in one area.  This should save you time in answering the same question over and over again.

WF-FAQ is a true Xoops module compared to its predecessor ‘FAQ Manager’ which was a hack of a script called FAQ Manager.  

Nearly all the previous code to that module has gone and been replaced with my own now and now this module makes use of many 

Xoops core functions.

--------------
How to install
--------------

WF-FAQ should be treated like any other Xoops module and should be installed exactly the same way.

The story goes something like this (those that are new to Xoops) :-)

1, Uninstall the package somewhere on your hard drive (Somewhere that you will remember)
2, Use a FTP program to upload the wffaq folder and its contents to Xoopsroot/modules/ on your server (should be NO need to make a new folder called wffaq.  But if you do, just copy the contents of the folder and not the folder with its contents).
3 Login to Xoops as an admin and click on 'Administration Menu' in user menu.
4 Once you are logged in as admin and in Xoops site admin, Hoover the mouse cursor over the 'system Admin' icon and then click on the 'Modules' link.
5 You should have arrived at 'Modules Administration' now, look down the module column list until you find the WF-FAQ admin icon (the one with Q: A:) and double click on it.  Xoops will now install the module and then prompt you to return 'back to 

Module Administration page' and then is the install complete.

If you receive any errors while installing, complete the whole procedure again from the beginning and if you still have the same error again go to the WF-FAQ website (http://wfsections.xoops2.com) and post the install error within the forum.

-----------------
How to Use WF-FAQ
-----------------

WF-Faq's comes in roughly five sections:

1.  The End User area. This is where your website users can view the Question and answers and really needs no work on your part.

2. FAQ Topics Admin. You can create, deleted and modify your FAQ topics from this area.  Use the text boxes to add your content to your new or modified FAQ.

	A. Create in:  This is the FAQ category that your FAQ will be created within. (Required)
	B. Question: Use this to enter your frequently asked question. (Required)
	C. Answer: Use this box to answer the above question. (Required)
	D. Summary. Give a short piece of text as to what this FAQ is.  

3. WF-FAQ Category Admin. Use this area to create FAQ Categories.  This helps break up each part of your FAQ into nice little neat chunks and this will help your users find what the are looking for quicker and easier. 
You can add as many Categories as you like, but these will all remain in the index page.

Again as with FAQ topics, use the text boxes to add or modify your categories.  The whole process of doing this rather easy and should be self-explanatory.

4. FAQ Submission (End User side). Your user can submit new FAQ directly to your site using the 'submit FAQ' link in the main menu. Once a user submits a new FAQ an email 'should' be sent to you telling you of this, the new FAQ is the ready for validation and can only be validated by in the WF-FAQ admin area.  These new FAQ will not show within the User side until you have done this.  

5. Validate new submissions: From here you can approve, delete or view a submitted FAQ.


WF-FAQ also makes use Xoops Search.

----------------------------
Getting help for this module
----------------------------

If you require help or have a question regarding this module, you can visit the WF-Section website 

(http://wfsections.xoops2.com) and using the forum, post your questions and I will try to answer these as best I can and when I can.

------------------------
Reporting bugs in WF-FAQ  
------------------------

As above, visit http://wfsections.xoops2.com and report the bug/s to the 'Mantis bug tracker', you can track your bug/s from there too.

-------
History
-------
14 July 2003 - v1.0.2

Whoops, I left out the topics.php file in the last version???? Added this back in and changed it to index.php
Fixed, the loop when no category was found.
Added German lang (Thanks FrankBlack).
Added Spanish lang (Thanks Horacio).
Changed version to 1.0.2

12 July 2003 1.0.1
added submit language tag in storyform.inc,
added category language tag to index.php and wffaq_category.html
Thanks to hsalazar for finding these :-)
removed wffaq.answer2.html as it was not needed.
Changed version to 1.0.1

12 July 2003 v1.00
No history and first public release. 

Credits and Thanks
Tom - For bugging me about this one. Cheers m8 :-).
FrankBlack - For the German Language files
Horacio - For His work on a new module that is about to come out (What the space at WF-Sections on this one) and his Spanish Language files.
hsalazar - For the bugs found.
 
