//
//  PushViewControllerAnimatedAppDelegate.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 12/02/2010.
//  Copyright Kioty Ltd 2010. All rights reserved.
//

#import "PushViewControllerAnimatedAppDelegate.h"
#import <AudioToolbox/AudioToolbox.h>

@implementation PushViewControllerAnimatedAppDelegate

@synthesize navigationController ;
@synthesize window ;

//@synthesize imageView ;

-(NSString *) dataFilePathTOKEN{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilenameTOKEN ] ;
	
}


-(NSString *) dataFilePath{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilename ] ;
	
}

//______________________________________________________________________________

- (void)applicationDidFinishLaunching:(UIApplication *)application {    
	
	//mapaMain = [[MKMapView alloc] init ];
	//mapaMain.showsUserLocation = TRUE ;
	
	//[mapaMain release] ;
	
	//Conexion internet para comunicarme con los push notificacion
	// Add registration for remote notifications
	[[UIApplication sharedApplication] 
	 registerForRemoteNotificationTypes:(UIRemoteNotificationTypeAlert | UIRemoteNotificationTypeBadge | UIRemoteNotificationTypeSound)];
	
	// Clear application badge when app launches
	application.applicationIconBadgeNumber = 0;
	//fin del push notifications
	
	estado_anterior = -1 ;
	estado_siguiente = - 1 ;
	fromWhere = 0 ;
	gifLanzado = FALSE ;
	
	imagenTermo = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"termometro.png"]] ;
	//imagenTermo2 = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"termometro.png"]] ;
	
	[window addSubview:navigationController.view];
	
	//Adding thermometer (UINavigationController) // Recordemos q tenemos 2 termometros, uno de cada view, y este
	[window addSubview:imagenTermo ]; 
	
	
	imagenTermo.contentMode = UIViewContentModeLeft ;
	
	imagenTermo.frame = CGRectMake(-5, 57, 95, 457);
	
	
	/*//Adding another button (HOME):
	 UIBarButtonItem * anotherButton = [[UIBarButtonItem] alloc] initWithTitle:@"Show" style:UIBarButtonItemStylePlain target:self action:@selector(refreshPropertyList:)];
	 self.navigationController.navigationItem.rightBarButtonItem = anotherButton ;
	 [anotherButton release] ;*/
	//self.navigationController.navigationItem.backBarButtonItem.title = @"Baccck" ;
	
	
	UIBarButtonItem * backBar = [[UIBarButtonItem alloc] initWithTitle:@"Backaaaa" style: UIBarButtonItemStyleDone target: nil action: nil ] ;
	self.navigationController.navigationItem.backBarButtonItem = backBar ;
	[backBar release] ;
	
	
	
	//NSLog(@"lalalalalla") ;
	
	//Cargo los datos del cliente:
	NSString * filePath = [self dataFilePath ] ;
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		
		NSLog(@"The Information file EXIST, so im going to work with it") ;
		
		NSArray * array = [[NSArray alloc] initWithContentsOfFile:filePath] ;
		
		//Cargo los datos segun el indice:
		
		/*
		 nameGLOBAL = [[NSString alloc] init] ;
		 nameGLOBAL = [array objectAtIndex:0] ;
		 
		 adressGLOBAL = [[NSString alloc] init] ; 
		 adressGLOBAL = [array objectAtIndex:1] ;
		 
		 bloodTypeGLOBAL = [[NSString alloc] init] ; 
		 bloodTypeGLOBAL = [array objectAtIndex:2] ;		
		 
		 DonorYesNoGLOBAL = [[NSString alloc] init] ; 
		 DonorYesNoGLOBAL = [array objectAtIndex:3] ;
		 
		 DonorNumberGLOBAL = [[NSString alloc] init] ; 
		 DonorNumberGLOBAL = [array objectAtIndex:4] ;
		 
		 NextofKinNameGLOBAL = [[NSString alloc] init] ; 
		 NextofKinNameGLOBAL = [array objectAtIndex:5] ;
		 
		 NextofKinNumberGLOBAL = [[NSString alloc] init] ; 
		 NextofKinNumberGLOBAL = [array objectAtIndex:6] ;
		 
		 NSLog(@"Name: %@", nameGLOBAL) ;
		 NSLog(@"Adress: %@", adressGLOBAL) ;	
		 NSLog(@"bloodTypeGLOBAL: %@", bloodTypeGLOBAL) ;
		 NSLog(@"DonorYesNoGLOBAL: %@", DonorYesNoGLOBAL) ;
		 NSLog(@"DonorNumberGLOBAL: %@", DonorNumberGLOBAL) ;
		 NSLog(@"NextofKinNameGLOBAL: %@", NextofKinNameGLOBAL) ;
		 NSLog(@"NextofKinNumberGLOBAL: %@", NextofKinNumberGLOBAL) ;	
		 */
		
		[array release] ;
		
		
	}
	else {
		
		//Si es la primera vez que se abre la aplicacion, voy a cargar los datos, pero con valores personalizados:
		
		NSLog(@"The Information file DOESNT EXIST, so im going to create it") ;
		
		nameGLOBAL = [[NSString alloc] init] ;
		nameGLOBAL = @"Your name" ;
		
		adressGLOBAL = [[NSString alloc] init] ; 
		adressGLOBAL = @"Your address" ;
		
		bloodTypeGLOBAL = [[NSString alloc] init] ;
		bloodTypeGLOBAL = @"Your blood type" ;
		
		DonorYesNoGLOBAL = [[NSString alloc] init] ; 
		DonorYesNoGLOBAL = @"No" ;
		
		DonorNumberGLOBAL = [[NSString alloc] init] ; 
		DonorNumberGLOBAL = @"Donor card number" ;
		
		NextofKinNameGLOBAL = [[NSString alloc] init] ;
		NextofKinNameGLOBAL = @"Your kin name" ;
		
		
		NextofKinNumberGLOBAL = [[NSString alloc] init] ;
		NextofKinNumberGLOBAL = @"Your kin number" ;
		
		NextofKinMailGLOBAL = [[NSString alloc] init] ;
		NextofKinMailGLOBAL = @"Your kin mail" ;
		
		dobGLOBAL = [[NSString alloc] init] ;
		dobGLOBAL = @"Your date of birth" ;
		
		allergiesGLOBAL = [[NSString alloc] init] ;
		allergiesGLOBAL = @"(Example) Pollen" ;
		
		medicationGLOBAL = [[NSString alloc] init] ;
		medicationGLOBAL = @"(Example) Heart tablets" ;
		
		alarmSound = [[NSString alloc] init] ;
		alarmSound = @"Off" ;
		
		mynotes = [[NSString alloc] init] ;
		mynotes = @"Write here your notes" ;
		
		existingConditions = [[NSString alloc] init] ;
		existingConditions = @"(Example) Asthma and diabetes" ;
		
		//Almaceno la informacion recien creada: (Creo mi fichero con la inform.)
		NSMutableArray * array = [[NSMutableArray alloc] init] ;
		
		[array addObject: nameGLOBAL ] ;				// 0
		[array addObject: adressGLOBAL ] ;				// 1
		[array addObject: bloodTypeGLOBAL ] ;			// 2	
		[array addObject: DonorYesNoGLOBAL ] ;			// 3 
		[array addObject: DonorNumberGLOBAL ] ;			// 4	
		[array addObject: NextofKinNameGLOBAL ] ;		// 5	
		[array addObject: NextofKinNumberGLOBAL ] ;		// 6
		[array addObject: NextofKinMailGLOBAL ] ;		// 7
		[array addObject: dobGLOBAL			] ;		    // 8	
		[array addObject: allergiesGLOBAL   ] ;		    // 9	
		[array addObject: medicationGLOBAL   ] ;	    // 10		
		[array addObject: alarmSound   ] ;	            // 11	
		[array addObject: mynotes  ] ;					// 12	
		[array addObject: existingConditions ] ;		// 13
		
		[array writeToFile:[ self dataFilePath ] atomically:YES ] ;
		[array release] ;
		
		NSLog(@"Information saved correctly");
		[[self navigationController] popViewControllerAnimated: YES ] ;
		
	}
	
	
	
	//NSLog(@"main view     -    latitude: %f", mapaMain.userLocation.coordinate.latitude) ;
 	//NSLog(@"main view     -    longitude: %f", mapaMain.userLocation.coordinate.longitude) ;	
	
	// Override point for customization after application launch
	[window makeKeyAndVisible];
}

/* 
 * --------------------------------------------------------------------------------------------------------------
 *  BEGIN APNS CODE 
 * --------------------------------------------------------------------------------------------------------------
 */

/**
 * Fetch and Format Device Token and Register Important Information to Remote Server
 */
- (void)application:(UIApplication *)application didRegisterForRemoteNotificationsWithDeviceToken:(NSData *)devToken {
	
#if !TARGET_IPHONE_SIMULATOR
	
	// Get Bundle Info for Remote Registration (handy if you have more than one app)
	NSString *appName = [[[NSBundle mainBundle] infoDictionary] objectForKey:@"CFBundleDisplayName"];
	NSString *appVersion = [[[NSBundle mainBundle] infoDictionary] objectForKey:@"CFBundleVersion"];
	
	// Check what Notifications the user has turned on.  We registered for all three, but they may have manually disabled some or all of them.
	NSUInteger rntypes = [[UIApplication sharedApplication] enabledRemoteNotificationTypes];
	
	// Set the defaults to disabled unless we find otherwise...
	NSString *pushBadge = @"disabled";
	NSString *pushAlert = @"disabled";
	NSString *pushSound = @"disabled";
	
	// Check what Registered Types are turned on. This is a bit tricky since if two are enabled, and one is off, it will return a number 2... not telling you which
	// one is actually disabled. So we are literally checking to see if rnTypes matches what is turned on, instead of by number. The "tricky" part is that the 
	// single notification types will only match if they are the ONLY one enabled.  Likewise, when we are checking for a pair of notifications, it will only be 
	// true if those two notifications are on.  This is why the code is written this way ;)
	if(rntypes == UIRemoteNotificationTypeBadge){
		pushBadge = @"enabled";
	}
	else if(rntypes == UIRemoteNotificationTypeAlert){
		pushAlert = @"enabled";
	}
	else if(rntypes == UIRemoteNotificationTypeSound){
		pushSound = @"enabled";
	}
	else if(rntypes == ( UIRemoteNotificationTypeBadge | UIRemoteNotificationTypeAlert)){
		pushBadge = @"enabled";
		pushAlert = @"enabled";
	}
	else if(rntypes == ( UIRemoteNotificationTypeBadge | UIRemoteNotificationTypeSound)){
		pushBadge = @"enabled";
		pushSound = @"enabled";
	}
	else if(rntypes == ( UIRemoteNotificationTypeAlert | UIRemoteNotificationTypeSound)){
		pushAlert = @"enabled";
		pushSound = @"enabled";
	}
	else if(rntypes == ( UIRemoteNotificationTypeBadge | UIRemoteNotificationTypeAlert | UIRemoteNotificationTypeSound)){
		pushBadge = @"enabled";
		pushAlert = @"enabled";
		pushSound = @"enabled";
	}
	
	// Get the users Device Model, Display Name, Unique ID, Token & Version Number
	UIDevice *dev = [UIDevice currentDevice];
	NSString *deviceUuid = dev.uniqueIdentifier;
    NSString *deviceName = dev.name;
	NSString *deviceModel = dev.model;
	NSString *deviceSystemVersion = dev.systemVersion;
	
	// Prepare the Device Token for Registration (remove spaces and < >)
	NSString *deviceToken = [[[[devToken description] 
							   stringByReplacingOccurrencesOfString:@"<"withString:@""] 
							  stringByReplacingOccurrencesOfString:@">" withString:@""] 
							 stringByReplacingOccurrencesOfString: @" " withString: @""] ;
	
	
	
	//NSString * filePathTOKEN = [self dataFilePathTOKEN ] ;
	//NSMutableArray * arrayTOKEN = [[NSMutableArray alloc] init] ;
	
	//[arrayTOKEN replaceObjectAtIndex:0 withObject: @"miauu" ] ;
	//[deviceToken writeToFile:[ self dataFilePathTOKEN ] atomically:YES ] ;
	//[arrayTOKEN release] ;
	
	
	NSString * filePathTOKEN = [self dataFilePathTOKEN ] ;
	NSMutableArray * arrayTOKEN = [[NSMutableArray alloc] init ]  ;
	
	
	//STEP2. Save the information: 
	
	//NSLog(deviceToken) ;
	
	//[arrayTOKEN replaceObjectAtIndex:0 withObject: @"deviceToken" ] ;
	
	[arrayTOKEN addObject: deviceToken ] ;
	
	[arrayTOKEN writeToFile:[ self dataFilePathTOKEN ] atomically:YES ] ; //Ha quedado guardado el toquen en token.plist.
	
	//[arrayTOKEN release] ;
	
	NSLog(@"Nuevo nombre guardado") ;
	
	
	NSLog( @"%@" ,[arrayTOKEN objectAtIndex:0] ) ;
	//NSLog( [arrayTOKEN objectAtIndex:1] ) ;
	
	
	// Build URL String for Registration
	// !!! CHANGE "www.mywebsite.com" TO YOUR WEBSITE. Leave out the http://
	// !!! SAMPLE: "secure.awesomeapp.com"
	
	//NSString *host = @"www.myoxygen.co.uk/pushaps";
	
	// !!! CHANGE "/apns.php?" TO THE PATH TO WHERE apns.php IS INSTALLED 
	// !!! ( MUST START WITH / AND END WITH ? ). 
	// !!! SAMPLE: "/path/to/apns.php?"
	
	//NSString *urlString = [NSString stringWithFormat:@"/apns.php?task=%@&appname=%@&appversion=%@&deviceuid=%@&devicetoken=%@&devicename=%@&devicemodel=%@&deviceversion=%@&pushbadge=%@&pushalert=%@&pushsound=%@", @"register", appName,appVersion, deviceUuid, deviceToken, deviceName, deviceModel, deviceSystemVersion, pushBadge, pushAlert, pushSound];
	
	// Register the Device Data
	// !!! CHANGE "http" TO "https" IF YOU ARE USING HTTPS PROTOCOL
	//NSURL *url = [[NSURL alloc] initWithScheme:@"http" host:host path:urlString];
    //NSURLRequest *request = [[NSURLRequest alloc] initWithURL:url];
	//NSData *returnData = [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
	//NSLog(@"Register URL: %@", url);
	//NSLog(@"Return Data: %@", returnData);
	
	
	// ******************************    MANDAR MENSAJE PARA RECORDAR CITA   *********************
	
	//A PARTIR DE AQUI ES COMO SI MANDARA EL MENSAJE!!!!!
	//Conecto a samplesTOKEN.php para que el lo haga todo! Le dare mi uid y mi token: Y punto! 
	//(Al menos por ahora, mas delante tendre que darle tambien la hora y la fecha de mi cita :) )
	
	//NSString *host2 = @"www.myoxygen.co.uk/pushaps";
	//NSString *urlString2 = [NSString stringWithFormat:@"/samplesTOKEN.php?device=%@&token=%@", deviceUuid, deviceToken];
	
	//NSURL *url2 = [[NSURL alloc] initWithScheme:@"http" host:host2 path:urlString2];
	//NSURLRequest *request2 = [[NSURLRequest alloc] initWithURL:url2];
	//NSData *returnData2 = [NSURLConnection sendSynchronousRequest:request2 returningResponse:nil error:nil];
	
	//NSLog(@"Register URL: %@", url2);
	//NSLog(@"Return Data: %@", returnData2);
	
	
	
#endif
}

/**
 * Failed to Register for Remote Notifications
 */
- (void)application:(UIApplication *)application didFailToRegisterForRemoteNotificationsWithError:(NSError *)error {
	
#if !TARGET_IPHONE_SIMULATOR
	
	NSLog(@"Error in registration. Error: %@", error);
	
#endif
}

/**
 * Remote Notification Received while application was open.
 */
- (void)application:(UIApplication *)application didReceiveRemoteNotification:(NSDictionary *)userInfo {
	
	
	NSLog(@"Mensaje recibido !!! *******") ;
	
	#if !TARGET_IPHONE_SIMULATOR
    
	NSLog(@"remote notification: %@",[userInfo description]);
	NSDictionary *apsInfo = [userInfo objectForKey:@"aps"];
	
	NSString *alertString = [apsInfo objectForKey:@"alert"];
	NSLog(@"Received Push Alert: %@", alertString);
	
	//leo creacion
	
	
	NSDictionary * dict = [NSDictionary alloc] ;
	dict = [apsInfo objectForKey:@"alert"];
	
	
	NSString * bodyAlertString = [dict objectForKey:@"body"];
	NSLog(@"Received Push body Alert: %@", bodyAlertString);
	
	//NSString * bodyString = [NSString alloc];
	//bodyString = 
	// fin leo creacion
	
	NSString *sound = [apsInfo objectForKey:@"sound"];
	 NSLog(@"Received Push Sound: %@", sound);
	 AudioServicesPlaySystemSound(kSystemSoundID_Vibrate);
	 
	 NSString *badge = [apsInfo objectForKey:@"badge"];
	 NSLog(@"Received Push Badge: %@", badge);
	 application.applicationIconBadgeNumber = [[apsInfo objectForKey:@"badge"] integerValue];
	
	
	UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"NHS Yorkshire and Humber" message: bodyAlertString delegate:self cancelButtonTitle:@"OK" otherButtonTitles: nil];
	
	[alert show];
	[alert release];
	
	
	#endif
}

-(void)alertView:(UIAlertView *) alertView clickedButtonAtIndex: (NSInteger)buttonIndex {
	
	if (buttonIndex == 0){
		//ok button		
		
	}
	
}	
- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {    
    
    // Override point for customization after application launch.
	
    // Add the view controller's view to the window and display.
    [window addSubview:navigationController.view];
    [window makeKeyAndVisible];
	
	application.applicationIconBadgeNumber = 0;
	
	// Handle launching from a notification
	UILocalNotification *notif = [launchOptions objectForKey:UIApplicationLaunchOptionsLocalNotificationKey];
	if (notif) {
		NSString* title = notif.alertAction;
		NSString* body = notif.alertBody;
		UIAlertView *alert = [[UIAlertView alloc] initWithTitle:title message: body delegate:self cancelButtonTitle:@"OK" otherButtonTitles: nil];
		[alert show];
		[alert release];	
	}
    return YES;
}

- (void)application:(UIApplication *)app didReceiveLocalNotification:(UILocalNotification *)notif {
	// Handle the notificaton when the app is running
	NSString* title = notif.alertAction;
	NSString* body = notif.alertBody;
	UIAlertView *alert = [[UIAlertView alloc] initWithTitle:title message: body delegate:self cancelButtonTitle:@"OK" otherButtonTitles: nil];
	[alert show];
	[alert release];
}
/* 
 * --------------------------------------------------------------------------------------------------------------
 *  END APNS CODE 
 * --------------------------------------------------------------------------------------------------------------
 */


- (void)dealloc {
	
	[nameGLOBAL release];
	[adressGLOBAL release];
	[bloodTypeGLOBAL release] ;
	[DonorYesNoGLOBAL release] ;
	[DonorNumberGLOBAL release] ;
	
	[navigationController release];
    [window release];
    [super dealloc];
}


@end