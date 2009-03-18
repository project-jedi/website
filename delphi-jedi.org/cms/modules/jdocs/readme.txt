Module name: WFChannels
Module Version: v1.02
Module Release Date: 20 Nov 2003
Module Author: Catzwolf
Module Author Email: Catzwolf@xoops.org

Module Description:

WFChannel is a Xoops v2.0.5 module and will not work with any other version of Xoops.

The purpose f this module is to quickly and easily add content to Xoops with minimal effort and time.
WFChannel main purpose is to content such as 'About Us', 'Privacy Statements' and other type of information such as this.

WFChannel also has another feature that will allow you to quickly add a 'link to us' page, you can easily upload the 
graphics and other information for this page.

How to Install

Copy the uploader.php file from the class directory to XOOPS_ROOT/class folder.  
This file is Important or you will not be able to upload HTML Files.  

Copy the contents of the module directory (WFChannel module) to XOOPS_ROOT_PATH/modules/ via an FTP client (program).
CHMOD the following folders to 0777 or 0755 (Depending on you server configuration):

/wfchannel/images/
/wfchannel/images/linkimages/
/wfchannel/html/

IMPORTANT: You must CHMOD these directories or you will have ERRORS while uploading your images. 

How to update

Point your browser to XOOPS_URL/modules/wfchannel/upgrade.php and click on the start button and that is it. 
The script will do the rest for you.

YOU MUST UPDATE THE MODULE FROM SYSTEM ADMIN once you have performed the upgrade.

How to Use:

WFChannel as five area's of WFChannel admin area:

1. General Settings
2. Main Admin page.
3. Create new Channel.
4. Link Page Admin.
5. Group Permissions
6. Refer a friend Admin.
7. Upload File.
8. Reorder Channels.

1. General Settings

The general settings allow you to change some aspects of WF-Channels Configuration.  Such as:

	a. 	HTML Upload Directory:  
		This is the directory where your static html pages will be uploaded and stored.  This is for use
		within a channel contents.
	b. 	Image Upload Directory
		This is the directory where your channel logo image will be uploaded and stored.
	c. 	Link Image Upload Directory
		This is the directory where your 'Link to us' images will be uploaded and stored.
	d. 	Maximum upload size  
		The maximum size limited allowed when uploading a file.  Covers both images and html files. Default is 50000kb
	e. 	Maximum upload Image width  
		The maximum image width size allowed when uploading an image.  Default is 600px
	f. 	Maximum upload Image height  
		The maximum image height size allowed when uploading an image.  Default is 600px
	g. 	Maximum number of Channels displayed on each page 
		The maximum number of channels to be displayed in the Main Admin Page.
	h. 	Allow anonymous users access to Link to Us?
		Using this switch will allow Anon users to view this channel	
	i. 	Allow anonymous users access to Refer a Friend?
		Using this switch will allow Anon users to view this channel
	j.	Comment Rules
		Allows you to change the way comments are dealt with when submitted		
	k.	Allow anonymous post in comments?
		Allow anon users to post comments
		
2. Main Admin page.
	Within this area you will be able to modify or delete current channels, you will also be able to view some information
	regarding each channel such as:
	ID: The Channel ID 
	Page Title: The title used for this channel.
	Weight: The channel weight, the order in which it is listed in either the main menu or main page. 
	Default Page:  Shows which channel is the default channel. 
	Main Page Link:  Shows which channel will be listed in the main default page. 
	Submenu Item: Shows which channel will be listed in the main menu as a sub-menu. 
	Action: Allows you to Modify or Delete a channel.
	
3. Create new Channel.
	This area will allow you to create a new Channel. The following option can be used to create this new Channel:
	
	a.	Channel Logo:  
		This is the image to be used for the logo of each channel page.
	b.	Channel Weight:	
		The weight of the Channel in the main menu or default channel page.  
	c.	Channel Title: 
		The title used if a channel will be displayed within the main menu as a sub-item. 
	d.	Channel headline:  
		The Title to be displayed within each channel page.
	e.	Static HTML:  
		A static HTML file that will be used as the channel content, this will override the 'page content' option below.	
	f. 	Channel Content:  
		This option is used to enter the channel content.  This can be straight text, html or X-code.     
 	g.	Disable HTML Tags:  
		Disable all HTML code in the channel page content.
  	h.	Disable Smiley Icons:   
		Disable all Smiley code in the channel page content.
  	i.	Disable XOOPS Codes:  
		Disable all XOOPS Codes in the channel page content.
  	j.	Use Linebreak Conversion: 
		Use this option if you are not using HTML code for the Page content, else switch off if HTML code is used.  
	k.	Set as Default Channel: 
		Sets the Channel page that will be the default when clicking the WFChannel link in the main menu.  This must be set. 
  	l.	Add as a submenu item:  
		Choose this option to display the channel in the main menu as a sub item.
  	m.	Add link to the main page:  
		Choose this option to display the Channel as a link within the default page.
  	n.	Allow Comments for this channel:  
		Allow users to submit and view comments for this channel.

4. Link Page Admin.
	
	This area of the WF-Channel Admin will allow you to configure the 'Link to us page'. 
	
	a.	Channel Logo:
		You can choose the image to be used as the 'Link to us' page logo; this is displayed at the top of the page. 
	b.	Channel Title: 
		The title used if a channel will be displayed within the main menu as a sub-item.
	c.	Title of the Text Link: 
		You can choose the title to be used as the text link title  
	d.	Image for Button link:
		The image to be used as your button link.
	e.	Image for Logo link:
		The image to be used as your logo link.
	f.	Image for Banner link:
		The image to be used as your banner link.
	g.	Add news feed option to link page:
		Allow your users to have the option of adding an RSS new feed to their website.
	h. 	Add as a submenu item?: 
		This option will allow you to add a 'link to Us' item in the main menu as a sub-item.
	i.	Add link to the main page?:  
		This option will allow you to add a 'link to Us' item in the default page as an item link.

5. Group Permissions
	These sets the group access permissions for each channel, uncheck or check the option for each channel permission.

	Link to Us and Refer to a friend are not controlled by Group permissions at this stage, you can however allow anon user
	to view these channels via WF-Channel configuration.  Either on/off.
	
6. Refer a friend Admin.
	
	This area of the WF-Channel Admin will allow you to configure the 'refer a friend' channel. 
	
	a.	Channel Logo:
		You can choose the image to be used as the 'refer a friend' page logo; this is displayed at the top of the page. 
	b.	Channel Title: 
		You can choose the title to be used for this channel  
	c.	Channel headline:  
		You can enter extra text as a short introduction, this can be HTML code, Xoops Code or plain text 
	d.	Use Senders Stored Email address?
		Setting this will make WF-Channel look for an email address for the sender stored within xoops.  If no email
		address is found it will default to a blank text box
	e.	Allow User to enter own Message?
		Setting this will allow your visitors to enter there own message when sending a refer a friend email, 
		else to text box will be shown.
	f.	Enter default message:
		Enter a default message to be sent with the refer a friend email.
	g. 	Add as a submenu item: 
		This option will allow you to add a 'refer a friend' item in the main menu as a sub-item.
	h. 	Add link to the main page: 
		This option will allow you to add a 'refer a friend' item in the main menu as a sub-item.
		
		NOTICE: I have not shown option between c & d, as these have already been covered in part 3.
		
		TIP:
		If you do not wish to have a message with the email, set 'Allow User to enter own Message' and 
		leave 'Enter default message:' blank.

7.	Upload File
	
	Ok, this one is a little bit different and is a bit hard to explain, but here I go :-)
	
	This upload area is dynamic, in such that it will show different parts for each selection.
	
	When you first enter the Upload file area you will be shown one selection box with three choices.
	These choices represent the different upload area's defines in the configuration.  You MUST select a field before
	the other parts of this are shown!
	
	1,	The main select box
		This will allow you to choose which area your file will be uploaded to
	2,	File viewer
		Depending on the choice in one, you will either be shown the image or HTML select box.  
		The image select box will allow you to view the selected image.  (I will add an option to view HTML files in the next version).
	3, 	Upload field
		This will allow you to choose and image or HTML file for uploading.
	4, 	Buttons
		The submit button will allow you to the file to the directory chosen in option 1. 
		The delete button will allow you to delete the file selected in option 2.		 
		
8.	Reorder Channels	

	This will allow you to easily and quickly reorder the channel weight, use the input text box to enter the
	new weight and click on the submit button.		
	
Module History:
v1.00 No history yet, just released.

v1.01 Bug fix and new additions. These will show major issues and additions.
	1, Fixed bug where anon users could not see channels within the main menu.
	2, Added refer a friend area to this module
	3, added switches in main config for Anon users to either be allowed access to link to us or refer a friend
	4, Added backendjs.php for link to us. 
	5, Plus others.

v1.0.2 Bug Fixes and new additions.
	1, 	Fixed no upgrade Script in package.
	2, 	Fixed missing database field.
	3, 	Fixed Submenu items not showing correctly.
	4, 	Fixed upload problems, this error was due to uploader.php error. Spent many hours trying to fix this before
		I found the little bugger.
	5,	Fixed bug where the files where not correctly CHMOD after upload.
	6,	Fixed the Group Access Error, silly me left an echo in there that should not have been lol.
	7,	Removed other small errors.
	8,	Removed all the upload fields and place them into one area for easy management.  
	9,	Added upload files area.
	10,	Added reorder channels area.
	11, Added Comments.
	12, Added Search.
	13,	Added and removed some cosmetic parts.
	14, Fixed bug where link to us and refer submenu item still displayed for anon user even thou they where not allowed.  