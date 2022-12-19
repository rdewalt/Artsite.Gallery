<?php
require_once 'header.inc';
include_once 'library.inc';
?>
<br clear="all">
<OL><b>Site Info: Crap worth reading probably once.</b>
<li>Site Security
	<ul>
		<li>This was one of the biggest things that went into the design of the site.  Passwords are <b>NEVER</b> sent plaintext.  The password is hashed (sha512 to be exact) before it leaves the browser, so the server never even sees the password in the clear.</li>
		<li>The entire site is wrapped in SSL.  In this day and age, there is no reason not to.</li>
		<li>There is no such thing as Perfect Security.  Anyone who tells you something is 100% impossible to hack, is selling something.  I try my best to make it Somewhat Harder to hack.</li>
		<li>We could actually make it <b>MORE</b> secure, however one of the balancing acts in site design is Securty vs User Experience.  Decisions were made to determine a balance between what is acceptable for users, and what is  secure.</li>
	</ul>
<li>User E-Mail
	<ul>
		<li>Users are REQUIRED to have a valid e-mail address on file.</li>
		<li>This address is never posted or listed on the site.</li>
		<li>The Admin is the only one with access.</li>
		<li>There will be no automated e-mailer that contacts you unless one is Explicitly Signed Up For.</li>
		<li>Spam sucks. I hate it too.  I will not spam you.  If I am sending you an e-mail, there is a Reason behind it.</li>
	</ul>
<li>File Formats
	<ul>
		<li>Currently we support GIF, JPEG, PNG for image formats.</li>
		<li>*coming soon* Text formats are limited currently to .TXT and .RTF And by .txt we mean "Unformatted plain vanilla, open it up in anything at all"</li>
		<li>*coming soon* Audio formats are limited to MP3 and OGG</li>
		<li>We do not currently support video or flash uploads.  We might in the future.</li>
		<br>* Video uploads, when/if supported will be transcoded into HTML5.
		<li>If you would like a file format supported, please let me know.</li>
	</ul>
</li>
<li>Moderators
	<ul>
		<li>Yes, there will be moderators.</li>
		<li>I, the Admin, have the final say on who can be a Moderator.  I will of course listen to objections.</li>
		<li>The only requirement for moderators is that they have 2FA enabled on their accounts. There is additional security requirements for Moderator Accounts.</li>
		<li>Moderators, when speaking AS a moderator, will be visually different.</li>
		<li>Yes, all moderator actions are tracked and audited.</li>
	</ul>
	</li>
<li>Donations/Ad Banners/Premium Membership
	<ul>
		<li>Donations go to the running of the site. I will do my best to make the cost-per-month available.</li>
		<li>Premium Members receive Extra features only.  Premium Members are still bound to All The Rules.</li>
		<li>A Premium Member may choose to have "flair" signifying such.</li>
		<li>There is no -required- membership fee.  Just because we have 'premium' doesn't mean a thing.  Hell, I might scrap it if there are enough donations.</li>
		<li>Ads are to pay the bills.  I intend for this site to be at least profitable enough to pay for itself.  Donations would be ideal.</li>
	</ul>
<li>Twitter Feeds
	<ul>
		<li><a href="https://twitter.com/UpDere">@UpDere</a> - The Official Twitter Feed</li>
		<li><a href="https://twitter.com/rdewlt">@rdewalt</a> - My personal feed.</li>
		<li>Others will be listed here as they become known.</li>
	</ul>
	</li>
</OL>

<?php include_once "footer.inc";?>
