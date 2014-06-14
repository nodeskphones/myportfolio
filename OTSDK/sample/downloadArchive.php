<?php

/**
* OpenTok PHP Library
* http://www.tokbox.com/
*
* Copyright (c) 2011, TokBox, Inc.
* Permission is hereby granted, free of charge, to any person obtaining
* a copy of this software and associated documentation files (the "Software"), 
* to deal in the Software without restriction, including without limitation 
* the rights to use, copy, modify, merge, publish, distribute, sublicense, 
* and/or sell copies of the Software, and to permit persons to whom the
* Software is furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included
* in all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
* OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
* THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN 
* THE SOFTWARE.
*/

require_once '../OpenTokSDK.php';

// To download, you need a 
$apiObj = new OpenTokSDK( API_Config::API_KEY, API_Config::API_SECRET );
$sessionId = ''; # Your session with an archiveId
$archiveId = ''; # Your Archive ID recorded in the session

// Make sure token has the moderator role
$token = $apiObj->generateToken($sessionId, RoleConstants::MODERATOR);

// This archiveId is generated from your javascript library after you record something

// Create an archive object
$archive = $apiObj->getArchiveManifest($archiveId, $token);
$resources = $archive->getResources();

// To get all videos, loop through the resources array
$vid = $resources[0]->getId();

// $url contains the file
$url = $archive->downloadArchiveURL($vid, $token);

echo $url;

?>
