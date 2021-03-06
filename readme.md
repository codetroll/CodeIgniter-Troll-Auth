# Troll-Auth - A CodeIgniter Authorization system

Somewhat Simple and Lightweight Authorisation System, slightly based on ion-auth (although they perform very different tasks)

<!---
[Official Website & Documentation](http://laravel.com)
-->
## Introduction

I have been looking around for an authorisation system for codeigniter, but so far I seem to have only been able to find authentication systems.
So - what is the difference you might say? 
An authentication system (like Ion-auth) in its base functionality helps decide if a user is who she/he claims to be. This is often accomplished by providing
a userid and a password the system can verify exist in its database.

But what if you need to control access to various part of your application? What if different users needs different kinds of access to the same resources?
This is what an authorisation system does. It assumes that some other part made sure that the user is allowed access to the application in the first place, once inside - the
authorisation system handles access and levels of access to the system. Examples could be:

is user XXX authorised to access resource YYY??
is user XXX authorised to perform action ZZZ??
is user XXX authorised to perform action ZZZ on resource YYY??

This library only attempts to help with the authorisation part. All that is needed from the authentication part is a unique userid (integer).

Beside for me using the Ion-auth README and the Ion-auth library to get the code formatting to look proper there really isn't much Ion-auth in this library.
In fact for them to work together, modifications needs to be done in Ion-auth as it only supports one group per user.
I will provide instructions on how to modify Ion-auth to work with this library. 

You should be aware however that you will have to update Ion-auth by hand when new updates arrive - wise men DO take backup before they embark on such a task ;)

## DOCUMENTATION
There is no documentation at the moment - just this README and the comments in the library and demo controller.

This library is based on two things - being a member of one or more groups and groups(or users) having access to different resources.
The base token of the library is a unique user id - in this implemenation it is an int(9), you can however change it to ie an email address if this suits you better.


### Groups
Groups are perhaps the easiest part of the library to understand - for it to work you can create different groups like admin, guest, member and so forth. 
Then you can add the users to one or more of these groups. In your application you can then check if the user is a member of a given group and select the proper code
to execute based on that.

```php
<?php
if ($this->troll_auth->is_user_member_of_group($user_id,$group_id))
{
	... do this....
} else if
{
	... do that ....
}
```
Since a user can be a member of more than one group you can use this to permit access to various things based on group membership. Furthermore different groups can have different
access levels to various resources.

This is however somewhat limited in use although it can bring you pretty far if your needs are small.

### - Resources
Resources makes things a bit more complex. Each resource is identified by a resource_id. This id must be present in what ever you want to protect. So if you have
a forum for example and want to control access to the various toplevel forums the toplevel forum requires that you add a resource_id field to that table.

** how do we handle authorisation to access controllers?
** can we auto add existing controllers to resources?


## Installation
Just copy the files from this package to the correspoding folder in your 
application folder.  For example, copy Troll_auth/config/troll_auth.php to 
system/application/config/troll_auth.php. 

After that add this to /application/config/constants.php:

```php
/*
|--------------------------------------------------------------------------
| Troll Auth access levels
|--------------------------------------------------------------------------
|
*/
define("READWRITE",6);
define("WRITE"    ,4);
define("READ"     ,2);
define("NO_ACCESS",0);
```
##USING THE LIBRARY:   
In the package you will find example usage code in the controllers and views 
folders.  The example code isn't the most beautiful code you'll ever see but 
it'll show you how to use the library and it's nice and generic so it doesn't 
require a MY_controller or anything but it will be easy to add render() methods 
if needed since there is only one load->view() per controller method.


Feel free to send me an email if you have any problems.  

## License

Troll-auth is not yet under any license.. Use at your own risk!!
