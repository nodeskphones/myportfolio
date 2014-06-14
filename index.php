<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Steffen Matt Profile</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- Fonts from Font Awsome -->
        <link rel="stylesheet" href="css/font-awesome.min.css">
         <!-- Magnific popup -->
        <link rel="stylesheet" href="css/magnific-popup.css">
        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="css/main.css">

        <!-- Color styles -->
        <link rel="stylesheet" href="css/colors/blue.css">
       <!-- <link rel="stylesheet" href="css/colors/yellow.css">-->
       <!-- <link rel="stylesheet" href="css/colors/red.css">-->
       <!--  <link rel="stylesheet" href="css/colors/purple.css">-->
       <!--  <link rel="stylesheet" href="css/colors/orange.css">-->
       <!--  <link rel="stylesheet" href="css/colors/green.css">-->
        
         <!-- Feature detection -->
        <script src="js/modernizr-2.6.2.min.js"></script>
        <!-- Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900,300italic,400italic,600italic,700italic,900italic' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Oxygen:400,700' rel='stylesheet' type='text/css'>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="js/plugins/html5shiv.js"></script>
          <script src="js/plugins/respond.min.js"></script>
        <![endif]-->
        <script src='https://swww.tokbox.com/webrtc/v2.0/js/TB.min.js'></script>
        
        
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-51946200-1', 'bespoked.biz');
  ga('send', 'pageview');

</script>

<?php
// OpenTok - session and API
require_once './OTSDK/OpenTokSDK.php';

$apiObj = new OpenTokSDK();
$sessionId = "2_MX40NDc1MzczMn5-V2VkIE1heSAwNyAxNjowMzozNiBQRFQgMjAxNH4wLjcyNjQxODF-fg";

?>
<script type="text/javascript">
      
      // EventSource handler for Twilio txt msg replies
       var source = new EventSource('twess.php');
         source.onmessage = function(e) {
       var NowTimeStr = e.data.substring(0,19);
       var MsgTimeStr = e.data.substring(20,39);
       var Msgbody = e.data.substring(40);
       var MsgTime = new Date(MsgTimeStr);
       var NowTime = new Date(NowTimeStr);
       var Tdiff = NowTime-MsgTime;
       if (Tdiff > 0 && Tdiff < 4000) {
       var txt=document.getElementById("TwilioMsg")
       txt.innerHTML= Msgbody;
       document.getElementById("TBStartCall").disabled = false;
       document.getElementById("TBdisconnect").disabled = false;
       } 
     }
</script>
</head>
<body>
<script type="text/javascript">

      // Initialize API key, session, and token...
      // Think of a session as a room, and a token as the key to get in to the room
      // Sessions and tokens are generated on your server and passed down to the client
      // var apiKey = "44753732";
      // var sessionId = "2_MX40NDc1MzczMn5-V2VkIE1heSAwNyAxNjowMzozNiBQRFQgMjAxNH4wLjcyNjQxODF-fg";
      		var apiKey = <?php print API_Config::API_KEY?>;
                var sessionId = '<?php print $sessionId; ?>';
                var token = '<?php print $apiObj->generate_token($sessionId); ?>';
                var session;
		var publisher;
		var subscribers = {};
		var VIDEO_WIDTH = 288;
		var VIDEO_HEIGHT = 216;

		TB.addEventListener("exception", exceptionHandler);
		
		// Un-comment the following to set automatic logging:
		TB.setLogLevel(TB.DEBUG);

		if (TB.checkSystemRequirements() != TB.HAS_REQUIREMENTS) {
			// alert("You don't have the minimum requirements to run this application."
				  // + "Please upgrade to the latest version of Flash.");
		} else {
			session = TB.initSession(sessionId);	// Initialize session

			// Add event listeners to the session
			session.addEventListener('sessionConnected', sessionConnectedHandler);
			session.addEventListener('sessionDisconnected', sessionDisconnectedHandler);
			session.addEventListener('connectionCreated', connectionCreatedHandler);
			session.addEventListener('connectionDestroyed', connectionDestroyedHandler);
			session.addEventListener('streamCreated', streamCreatedHandler);
			session.addEventListener('streamDestroyed', streamDestroyedHandler);
		}

		//--------------------------------------
		//  LINK CLICK HANDLERS
		//--------------------------------------

		/*
		If testing the app from the desktop, be sure to check the Flash Player Global Security setting
		to allow the page from communicating with SWF content loaded from the web. For more information,
		see http://www.tokbox.com/opentok/docs/js//tutorials/helloworld.html#localTest
		*/
		function connect() {
			session.connect(apiKey, token);
		}

		function disconnect() {
			session.disconnect();
                        hide("TBdisconnect");
                        show("TBStartCall");
                        document.getElementById("TBStartCall").disabled = true;
			
		}

		// Called when user wants to start publishing to the session
		function startPublishing() {
			if (!publisher) {
				var parentDiv = document.getElementById("myCamera");
				var publisherDiv = document.createElement('div'); // Create a div for the publisher to replace
				publisherDiv.setAttribute('id', 'opentok_publisher');
				parentDiv.appendChild(publisherDiv);
				var publisherProps = {width: VIDEO_WIDTH, height: VIDEO_HEIGHT};
				publisher = TB.initPublisher(apiKey, publisherDiv.id, publisherProps);  // Pass the replacement div id and properties
				session.publish(publisher);
                                hide("TBStartCall");
                                show("TBdisconnect");
				
			}
		}

		function stopPublishing() {
			if (publisher) {
				session.unpublish(publisher);
			}
			publisher = null;

			
		}

		//--------------------------------------
		//  OPENTOK EVENT HANDLERS
		//--------------------------------------

		function sessionConnectedHandler(event) {
			// Subscribe to all streams currently in the Session
			for (var i = 0; i < event.streams.length; i++) {
				addStream(event.streams[i]);
			}
			
		}

		function streamCreatedHandler(event) {
                  // alert("stream detected");
			// Subscribe to the newly created streams
			for (var i = 0; i < event.streams.length; i++) {
				addStream(event.streams[i]);
			}
		}

		function streamDestroyedHandler(event) {
			// This signals that a stream was destroyed. Any Subscribers will automatically be removed.
			// This default behaviour can be prevented using event.preventDefault()
		}

		function sessionDisconnectedHandler(event) {
			// This signals that the user was disconnected from the Session. Any subscribers and publishers
			// will automatically be removed. This default behaviour can be prevented using event.preventDefault()
			publisher = null;

			
		}

		function connectionDestroyedHandler(event) {
			// This signals that connections were destroyed
		}

		function connectionCreatedHandler(event) {
			// This signals new connections have been created.
		}

		/*
		If you un-comment the call to TB.setLogLevel(), above, OpenTok automatically displays exception event messages.
		*/
		function exceptionHandler(event) {
			alert("Exception: " + event.code + "::" + event.message);
		}

		//--------------------------------------
		//  HELPER METHODS
		//--------------------------------------

		function addStream(stream) {
			// Check if this is the stream that I am publishing, and if so do not publish.
			if (stream.connection.connectionId == session.connection.connectionId) {
				return;
			}
			var subscriberDiv = document.createElement('div'); // Create a div for the subscriber to replace
			subscriberDiv.setAttribute('id', stream.streamId); // Give the replacement div the id of the stream as its id.
			document.getElementById("subscribers").appendChild(subscriberDiv);
			var subscriberProps = {width: VIDEO_WIDTH, height: VIDEO_HEIGHT};
			subscribers[stream.streamId] = session.subscribe(stream, subscriberDiv.id, subscriberProps);
		}

		function show(id) {
			document.getElementById(id).style.display = 'block';
		}

		function hide(id) {
			document.getElementById(id).style.display = 'none';
		}
                
                
function SendSMS() {
    // 1. Create XHR instance - Start
    var xhr;
    if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        xhr = new ActiveXObject("Msxml2.XMLHTTP");
    }
    else {
        throw new Error("Ajax is not supported by this browser");
    }
    // 1. Create XHR instance - End
    
    // 2. Define what to do when XHR feed you the response from the server - Start
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status == 200 && xhr.status < 300) {
                document.getElementById('div1').innerHTML = xhr.responseText;
            }
        }
    }
    // 2. Define what to do when XHR feed you the response from the server - Start

    var Sender = document.getElementById("SMSSender").value;
    var Msg = document.getElementById("SMSMsg").value;

    // 3. Specify your action, location and Send to the server - Start 
    xhr.open('POST', 'twsendsms.php');
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("Sender=" + Sender +"&message="+ Msg);
    document.getElementById('TwilioMsg').innerHTML = "Message sent. Wait for my reply..."
    
    // 3. Specify your action, location and Send to the server - End
    
   // Connect user to TB session
   connect();
}

</script>

  <div class="navbar navbar-default navbar-static-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav" id="main-menu">
            <li><a href="#page-welcome">Home</a></li>
            <li><a href="#page-profile">Profile</a></li>
         <!--   <li><a href="#page-skills">Skills</a></li> -->
            <li><a href="#page-education">Education</a></li>
            <li><a href="#page-experience">Experience</a></li>
            <li><a href="#page-portfolio">Portfolio</a></li>
            <li><a href="#page-contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    <!-- welcome begins -->
    <section id="page-welcome" class="page-welcome">
      <div>
        <!-- disable slider controls
          <ul class="slider-controls">
              <li><a id="vegas-next" class="next" href="#"></a></li>
              <li><a id="vegas-prev" class="prev" href="#"></a></li>
          </ul>
  -->
        </div>
        <div class="container">
            <div class="row">
                <header class="centered">
                    <h1>Steffen Matt</h1>
                    <p>PRODUCT MANAGEMENT PROFESSIONAL</p>
                </header>
                <div class="social-icons">
                        <a href="https://twitter.com/thecloudpm" class="btn btn-round btn-clear btn-twitter"><i class="fa fa-twitter"></i></a>
                        <a href="https://www.linkedin.com/in/steffenmatt/" class="btn btn-round btn-clear btn-linkedin"><i class="fa fa-linkedin-square"></i></a>
                         <a href="http://www.pinterest.com/smattp/" class="btn btn-round btn-clear btn-pinterest"><i class="fa fa-pinterest"></i></a>
                    
                </div>
                <a href="Resume Steffen Matt.pdf" class="btn btn-default hire-me">Download my resume</a>
		<a href="#page-contact" class="btn btn-default hire-me">Call Me</a>
            </div>
        </div>
    </section><!-- welcome ends -->
   
    <!-- profile begins -->
    <section id="page-profile" class="page-profile">
      <div class="container">
                <header class="section-header">
                    <h2 class="section-title">Personal Profile</h2>
                    <div class="spacer"></div>
                    <p class="section-subtitle">Business has only two functions - marketing and innovation (Peter Drucker)</p>
                </header>
            <div class="row">
              <div class="col-md-3">
                 <div class="profile"><img src="img/profile.png" alt="profile"/></div> 
              </div>
               <div class="col-md-9">
                <p>I'm Steffen Matt, a product management professional with 10+ years of experience in conceptulizing and shipping </p>
                <p>Cloud solutions. I learned the ropes at WebEx, one of the pioneers in SaaS and online collaboration. My time there </p>
                <p>has taught me lots about product, process and leadership. I've been spending the last couple of years applying my</p>
                <p>lessons learned at smaller companies and start-ups. You can find me running the gamet from setting business and</p>
                <p>product strategy, mentoring talented PMs, meeting with customers & partners, sketching out new designs and solutions.</p>
                <br>
                <p>On the weekends, you can find me sailing, flying, cycling, running and spending time with my beautiful wife and</p>
                <p>daughter.</p>
              </div>
            </div>
      </div> 
    </section><!-- profile ends -->

  
    <!-- experience begins -->
     <section id="page-experience" class="page-experience">
       <div class="container">
                <header class="section-header">
                    <h2 class="section-title"><span>Experience</span></h2>
                     <div class="spacer"></div>
                    <p class="section-subtitle">Be stubborn on vision but flexible on details (Jeff Bezos)</p>
                </header>
                <div class="row">
                 <div class="col-md-4">
                  <article class="experience">
                    <header>
                      <h3>Jabze.com</h3>
                      <p>Head of Product / Sunnyvale, CA / 2013 - Current</p>
                    </header>
                      <p>Drive product strategy development and execution for a new disruptive mobile communications platform for business. </p>
                  </article>
                </div>
                  <div class="col-md-4">
                  <article class="experience">
                    <header>
                      <h3>ConnectSolutions Inc</h3>
                       <p>Director, PM / San Francisco, CA / 2011 - 2013</p>
                    </header>
                      <p>Owned product strategy, product roadmaps and product life-cycle management of ConnectSolutions Unified Communications product portfolio. Mentored a small team of talented product managers.</p>
                  </article>
                </div>
                <div class="col-md-4">
                <article class="experience">
                  <header>
                    <h3>Cisco WebEx</h3>
                     <p>Manager, PM / San Francisco, CA / 2003 - 2011</p>
                  </header>
                    <p>Left Cisco WebEx as an experienced product management executive after conceptualizing and releasing 10+ innovative communication-enabled SaaS solutions.</p>
                </article>
                </div>
            </div>
        </div>
    </section><!-- experience ends -->
     
    <!-- portfolio begins -->
    <section id="page-portfolio" class="page-portfolio">
      <div class="container">
          <header class="section-header">
            <h2 class="section-title"><span>Portfolio</span></h2>
            <div class="spacer"></div>
            <p class="section-subtitle">Experience tells you what to do. Confidence allows you to do it.
- Stan Smith </p>
          </header>

          <!-- <div id="grid-controls-wrapper"> -->
          <ul class="nav nav-pills center-pills grid-controls">
            <li class="active"><a href="#home" data-toggle="tab">Jabze</a></li>
            <li><a href="#CoSo" data-toggle="tab">ConnectSolutions</a></li>
            <li><a href="#cisco" data-toggle="tab">Cisco WebEx</a></li>
          </ul>
        <!-- </div> -->
	  
        <div class="tab-content">
          <div class="tab-pane active" id="home">
            <div class="col-md-6 portfolio">
              <img src="img/jabze/Jabze.png"  alt="" >
            </div>
            <div class="col-md-6 portfolio">
	              <p><strong>The service:</strong></p>
                      <p>Jabze.com offers secure mobile communication for businesses. Today&#39;s workforce is too dispresed and mobile for email and traditional IM to be effective.</p>
                      <p>With Jabze, teams can stay connected and productive anywhere, at anytime with secure text messaging, audio conferencing
		      video conferening and deskop sharing.</p>
		      
		      <p><strong>My role:</strong></p>
		      <ul>
			<li>Define and implement business strategy with founder</li>
			<li>Setup product strategy and define product roadmaps</li>
			<li>Lead product design and implementation - web, desktop, iOS, Android clients</li>
			<li>Set growth hacking strategy and oversee implementation</li>
			<li>Drive business development efforts</li>
		      </ul>
            </div>
          </div>
	  
          <div class="tab-pane" id="CoSo">
            <div class="col-md-6 portfolio">
              <img src="img/coso/mpclync.png"  alt="" >
            </div>
            <div class="col-md-6">
              <p>Reutlingen University is one of Germany&#39;s leading universities, offering international academic programs with close ties to industry and commerce. </p>
              <p>Ranked highly in major European publications. One of Hewlett-Packard&#39;s preferred recruiting grounds in the &#39;90.</p>                 
            </div>
          </div>
	  
          <div class="tab-pane" id="cisco">
            <div class="col-md-6 portfolio">
                  <img src="img/cisco/Vzz.png"  alt="" >
<!--		  <br>
		  <img src="img/cisco/veoc.jpg"  alt="" >
		  <br>
		  <img src="img/cisco/connectspace.png"  alt="" > -->
	    </div>
	    
	    <div class="col-md-6">
              <p>Reutlingen University is one of Germany&#39;s leading universities, offering international academic programs with close ties to industry and commerce. </p>
              <p>Ranked highly in major European publications. One of Hewlett-Packard&#39;s preferred recruiting grounds in the &#39;90.</p>                 
	    </div>
	  </div>
   
        </div> <!-- Tabs -->
      </div>  <!-- container -->
    </section> <!-- portfolio ends -->
    
    <section id="page-education" class="page-education">
       <div class="container">
                <header class="section-header">
                    <h2 class="section-title"><span>Education</span></h2>
                     <div class="spacer"></div>
                    <p class="section-subtitle">German degree in Computer Science and Business Administration</p>
                </header>
                <div class="row">
                 <div class="col-md-6">
                  <article class="education">
                    <header>
                      <h3>Reutlingen University </h3>
                      <p>Dipl. Informatiker (FH) <strong>Graduated:</strong> 1997</p>
                    </header>
                      <p>Reutlingen University is one of Germany&#39;s leading universities, offering international academic programs with close ties to industry and commerce. </p>
                      <p>Ranked highly in major European publications. One of Hewlett-Packard&#39;s preferred recruiting grounds in the &#39;90.</p>                 
                  </article>
                </div>

                <div class="col-md-6">
                <img src="img/UCreutlingen.jpg"  alt="" >
                </div>
            </div>
       </div>
    </section><!-- education ends -->
    <!-- contact begins -->
     <!-- Video calling begins -->
   <section id="page-contact" class="page-contact">
      <div class="container">
	<header class="section-header">
            <h2 class="section-title">Contact - Call me now</h2>
	</header>
            <div class="row">
	       <div class="col-md-4">                
               <header class="section-header">
                    <p class="section-subtitle-left">1. Let me know you're here:</p>
	      </header>
	    <form  class="form-horizontal" id="contact-form">
            <div class="control-group">
             <div class="controls">
                <input type="text" name="SMSSender" id="SMSSender" placeholder="Your name" class="form-control input-lg ">
             </div>
            </div>
            <div class="control-group">
            <div class="controls">
                <input type="text" name="SMSMsg" id="SMSMsg" placeholder="Your text msg" class="form-control input-lg">
            </div>
            </div>
	    <br>
	    <div class="control-group">
            <div class="controls">
	      <label class="control-label" for="TwilioMsg">Replies from Steffen:</label>
                <textarea name="message" id="TwilioMsg" rows="1" class="form-control input-lg"></textarea>
            </div>
        </div>
            <div class="form-actions">
              <input type="button" class="btn btn-default btn-lg btn-block" value="Send Txt Msg" id ="SMSButton" onClick="javascript:SendSMS()" >
            </div>
          </form>
	       </div>
	       <div class="col-md-8">
		<header class="section-header">
                    <p class="section-subtitle-left">2. Click <strong>Join Video Call</strong> button below when I&#39;m online:</p>   
	         </header>
		<div class="row">
		 <input type="button" class="btn btn-default-no-margin btn-lg btn-block" value="Join Video Call" id ="TBStartCall" onClick="javascript:startPublishing()" disabled/>
                 <input type="button" class="btn btn-default-no-margin btn-lg btn-block" value="Leave" id ="TBdisconnect" onClick="javascript:disconnect()" style="display: none;"/>
                </div>
                 <div class="row">
                 <div class="float">
                   <div id="myCamera"></div>
                 </div>   
                 <div class="float">
                   <div id="subscribers"></div>
                 </div>
		</div>
	       </div>
               
      </div> 
    </section><!-- Video Call ends -->
    


        <script src="js/jquery-1.10.2.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.scrollTo.js"></script>
        <script src="js/jquery.nav.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/jquery.easypiechart.min.js"></script>
        <script src="js/jquery.vegas.min.js"></script>
        <script src="js/jquery.isotope.min.js"></script>
        <script src="js/jquery.magnific-popup.min.js"></script>
        <script src="js/jquery.validate.js"></script>
        <script src="js/waypoints.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
