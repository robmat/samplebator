//
//  appointmentsViewController.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 26/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "appointmentsViewController.h"
#import <AVFoundation/AVAudioPlayer.h>
#import <AudioToolbox/AudioToolbox.h>
#import "PushViewControllerAnimatedAppDelegate.h"

@implementation appointmentsViewController

//@synthesize rootTabBarController ;
//@synthesize textoSetReminder ;

-(NSString *) dataFilePathTOKEN{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilenameTOKEN ] ;
	
}



-(IBAction)emergenciesButtonAction{
	
	//Antes era el boton para emergencias, pero lo cambiamos a una cita en general, no solo para emergencias.
	serviceLabel.text = @"medicine to take" ;
	
	emergenciesButton.alpha = 1 ;
	hospitalButton.alpha = 0.3 ;
	gpButton.alpha = 0.3 ;
	dentistButton.alpha = 0.3 ;
	
}

-(IBAction)hospitalButtonAction{
	
	serviceLabel.text = @"hospital visit" ;
	
	emergenciesButton.alpha = 0.3 ;
	hospitalButton.alpha = 1 ;
	gpButton.alpha = 0.3 ;
	dentistButton.alpha = 0.3 ;
	
}


-(IBAction)gpButtonAction{
	serviceLabel.text = @"general practice visit" ;
	
	emergenciesButton.alpha = 0.3 ;
	hospitalButton.alpha = 0.3 ;
	gpButton.alpha = 1 ;
	dentistButton.alpha = 0.3 ;
	
}


-(IBAction)dentistButtonAction{
	serviceLabel.text = @"dentist visit" ;
	emergenciesButton.alpha = 0.3 ;
	hospitalButton.alpha = 0.3 ;
	gpButton.alpha = 0.3 ;
	dentistButton.alpha = 1 ;
	
}

-(IBAction)min30ButtonAction{
	appointmentBefore.text = @"30 minutes" ;
	
	min30Button.alpha = 1 ;
	min60Button.alpha = 0.3 ;
	min90Button.alpha = 0.3 ; 
	hour24Button.alpha = 0.3 ;
	
	
	
}
-(IBAction)min60ButtonAction{
	appointmentBefore.text = @"60 minutes" ;
	min30Button.alpha = 0.3 ;
	min60Button.alpha = 1 ;
	min90Button.alpha = 0.3 ; 
	hour24Button.alpha = 0.3 ;
	
	
}
-(IBAction)min90ButtonAction{
	appointmentBefore.text = @"90 minutes" ;
	min30Button.alpha = 0.3 ;
	min60Button.alpha = 0.3 ;
	min90Button.alpha = 1 ; 
	hour24Button.alpha = 0.3 ;
	
	
}
-(IBAction)hor24ButtonAction{
	appointmentBefore.text = @"24 hours" ;
	min30Button.alpha = 0.3 ;
	min60Button.alpha = 0.3 ;
	min90Button.alpha = 0.3 ; 
	hour24Button.alpha = 1 ;	
}


-(IBAction)setReminderAction{
  	
	if ( [serviceLabel.text  isEqualToString: @"Service"] ) {
		UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Set Reminder Menu" message:@"Please, set the service. Select using the buttons."
													   delegate:self cancelButtonTitle:@"OK" otherButtonTitles: nil];
		
		[alert show];
		[alert release];
	}
	else {
		if ( [appointmentBefore.text isEqualToString: @"Time"] ) {
			UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Set Reminder Menu" message:@"Please, set the time before for the reminder. Select using the buttons."
														   delegate:self cancelButtonTitle:@"OK" otherButtonTitles: nil];
			
			[alert show];
			[alert release];
		}
		else {
			NSLog(@"Envio el reminder!") ;
			UILocalNotification* notification = [[UILocalNotification alloc] init];
			notification.alertAction = @"Reminder";
			notification.alertBody = [NSString stringWithFormat:@"%@ %@ %@ %@", @"You have a scheduled", serviceLabel.text, @"within", appointmentBefore.text];
			NSDate* date = datePicker.date;
			
			int segundosARestar = 0 ;
            if ( [appointmentBefore.text isEqualToString: @"30 minutes" ]){
                NSLog(@"Voy a restar 30 minutos a la hora elegida por el appointment") ;
                segundosARestar = -1800 ;
                
            }else {
                if ([appointmentBefore.text isEqualToString: @"60 minutes"]){
                    NSLog(@"Voy a restar 60 minutos a la hora elegida por el appointment") ;
                    segundosARestar = -3600 ;
                }
                else {
                    if([appointmentBefore.text isEqualToString: @"90 minutes"]){
                        NSLog(@"Voy a restar 90 minutos a la hora elegida por el appointment") ;
                        segundosARestar = -5400 ;
                    }
                    else {
                        if([appointmentBefore.text isEqualToString: @"24 hours"]){
                            NSLog(@"Voy a restar 24 hours a la hora elegida por el appointment") ;
                            segundosARestar = -86400 ;
                        }
                    }
                }
            }
			NSTimeZone* timeZone = [NSTimeZone defaultTimeZone];
			date = [date dateByAddingTimeInterval:segundosARestar];
			notification.fireDate = date;
			notification.timeZone = timeZone;
			notification.soundName = UILocalNotificationDefaultSoundName;
			notification.applicationIconBadgeNumber = -1;
			[[UIApplication sharedApplication] scheduleLocalNotification: notification];
			UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Reminder set" message:@"This reminder has been set." delegate:self cancelButtonTitle:@"OK" otherButtonTitles: nil];
			[alert show];
			[alert release];	
			
		}
	}
}


-(IBAction)sendReminder {

	/*
	NSString * texto = [NSString stringWithFormat:@"%@%@%@%@%@",  @"NHS Yorkshire and Humber: You have an appointment. " , serviceLabel.text , @" in ", appointmentBefore.text , @"." ] ;
	
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
		pushBadge = @"disabled";
	}
	else if(rntypes == UIRemoteNotificationTypeAlert){
		pushAlert = @"enabled";
	}
	else if(rntypes == UIRemoteNotificationTypeSound){
		pushSound = @"enabled";
	}
	else if(rntypes == ( UIRemoteNotificationTypeBadge | UIRemoteNotificationTypeAlert)){
		pushBadge = @"disabled";
		pushAlert = @"enabled";
	}
	else if(rntypes == ( UIRemoteNotificationTypeBadge | UIRemoteNotificationTypeSound)){
		pushBadge = @"disabled";
		pushSound = @"enabled";
	}
	else if(rntypes == ( UIRemoteNotificationTypeAlert | UIRemoteNotificationTypeSound)){
		pushAlert = @"enabled";
		pushSound = @"enabled";
	}
	else if(rntypes == ( UIRemoteNotificationTypeBadge | UIRemoteNotificationTypeAlert | UIRemoteNotificationTypeSound)){
		pushBadge = @"disabled";
		pushAlert = @"enabled";
		pushSound = @"enabled";
	}
	
	
	// Get the users Device Model, Display Name, Unique ID, Token & Version Number
	UIDevice *dev = [UIDevice currentDevice];
	NSString *deviceUuid = dev.uniqueIdentifier;
    NSString *deviceName = dev.name;
	NSString *deviceModel = dev.model;
	NSString *deviceSystemVersion = dev.systemVersion;
	
	//Puedo cogerlo todo excepto el token! Por eso me lo traigo de memoria!
	
	NSString *deviceToken = @"" ;
	
	
	NSString * filePathTOKEN = [self dataFilePathTOKEN ] ;
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePathTOKEN]) {
		NSArray * arrayTOKEN = [[NSArray alloc] initWithContentsOfFile:filePathTOKEN] ;
		
		deviceToken = [arrayTOKEN objectAtIndex:0] ; 
		NSLog(@"%@", deviceToken) ;
	
	}	

	// Build URL String for Registration
	NSString *host = @"www.myoxygen.co.uk/pushaps";
	
	// Con esto actualizo el device
	NSString *urlString = [NSString stringWithFormat:@"/apns.php?task=%@&appname=%@&appversion=%@&deviceuid=%@&devicetoken=%@&devicename=%@&devicemodel=%@&deviceversion=%@&pushbadge=%@&pushalert=%@&pushsound=%@", @"register", appName,appVersion, deviceUuid, deviceToken, deviceName, deviceModel, deviceSystemVersion, pushBadge, pushAlert, pushSound];
	
	// Register the Device Data
	// !!! CHANGE "http" TO "https" IF YOU ARE USING HTTPS PROTOCOL
	NSURL *url = [[NSURL alloc] initWithScheme:@"http" host:host path:urlString];
    NSURLRequest *request = [[NSURLRequest alloc] initWithURL:url];
	NSData *returnData = [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
	NSLog(@"Register URL: %@", url);
	NSLog(@"Return Data: %@", returnData);
	
	
	// ******************************    MANDAR MENSAJE PARA RECORDAR CITA   *********************
	
	//A PARTIR DE AQUI ES COMO SI MANDARA EL MENSAJE!!!!!
	//Conecto a samplesTOKEN.php para que el lo haga todo! Le dare mi uid y mi token: Y punto! 
	//(Al menos por ahora, mas delante tendre que darle tambien la hora y la fecha de mi cita :) )
	
	//Con esto mando el mensaje
	
	NSString *host2 = @"www.myoxygen.co.uk/pushaps";
	NSString *urlString2 = [NSString stringWithFormat:@"/samplesTOKEN.php?device=%@&token=%@&message=%@", deviceUuid, deviceToken, texto];
	
	NSURL *url2 = [[NSURL alloc] initWithScheme:@"http" host:host2 path:urlString2];
	NSURLRequest *request2 = [[NSURLRequest alloc] initWithURL:url2];
	NSData *returnData2 = [NSURLConnection sendSynchronousRequest:request2 returningResponse:nil error:nil];
	
	NSLog(@"Register URL: %@", url2);
	NSLog(@"Return Data: %@", returnData2);
	
	#endif
	*/
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
	
	/*
	#if !TARGET_IPHONE_SIMULATOR
    
	NSLog(@"remote notification: %@",[userInfo description]);
	NSDictionary *apsInfo = [userInfo objectForKey:@"aps"];
	
	NSString *alert = [apsInfo objectForKey:@"alert"];
	NSLog(@"Received Push Alert: %@", alert);
	
	NSString *sound = [apsInfo objectForKey:@"sound"];
	NSLog(@"Received Push Sound: %@", sound);
	AudioServicesPlaySystemSound(kSystemSoundID_Vibrate);
	
	NSString *badge = [apsInfo objectForKey:@"badge"];
	NSLog(@"Received Push Badge: %@", badge);
	application.applicationIconBadgeNumber = [[apsInfo objectForKey:@"badge"] integerValue];
	
	#endif
	 */
}

/* 
 * --------------------------------------------------------------------------------------------------------------
 *  END APNS CODE 
 * --------------------------------------------------------------------------------------------------------------
 */


-(IBAction)butonA{

	/*[UIView beginAnimations:nil context:NULL];
	[UIView setAnimationDuration:1.0];
	[UIView setAnimationTransition:UIViewAnimationTransitionFlipFromRight forView:view1 cache:YES];
	
	[UIView setAnimationTransition:UIViewAnimationTransitionFlipFromRight forView:view1 cache:YES];
	
	
	
	//[view1 addSubview:view2 ];
	
	[UIView commitAnimations];
	 */
}

-(IBAction)buttonAAction{


}

/*
 // The designated initializer.  Override if you create the controller programmatically and want to perform customization that is not appropriate for viewDidLoad.
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    if (self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil]) {
        // Custom initialization
    }
    return self;
}
*/

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	[super viewDidLoad];
	self.title = @"Reminders" ;
	//imageSendingReminder.hidden = TRUE ;
	
	//Muestra today en el pickerDate
	NSDate * todayPICKER = [NSDate dateWithTimeIntervalSinceNow:0] ;
	dateString = [[NSString alloc] init] ;
	datePicker.date = todayPICKER ;
	
	
	//Muestro por pantalla el dia del picker y la hora, para mostrar que es correcto:
	NSDateFormatter *dateFormat = [[NSDateFormatter alloc] init];
	[dateFormat setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
	dateString = [dateFormat stringFromDate:datePicker.date ];
	NSLog(@"date: %@", dateString) ;
	
	
	//Initilation code
	[datePicker addTarget:self action:@selector (changeDataInLabel:) forControlEvents:UIControlEventValueChanged] ;
	[datePicker release] ;
	
	//viewSendingReminder.hidden = TRUE ;
	
	//NSDate * appintmentDate = [[NSDate alloc] init] ;

}

//_____________________________________________________________________________________


- (void)changeDataInLabel:(id)sender{
	
	
	//NSDate * today = [NSDate date ] ;
	NSDateFormatter *dateFormat = [[NSDateFormatter alloc] init];
	[dateFormat setTimeZone: [NSTimeZone timeZoneForSecondsFromGMT:0]];
	[dateFormat setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
	
	dateString = [dateFormat stringFromDate:datePicker.date ];
	
	NSLog(@"date: %@", dateString) ;
	[dateFormat release] ;
}



/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

- (void)didReceiveMemoryWarning {
	// Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
	
	// Release any cached data, images, etc that aren't in use.
}

- (void)viewDidUnload {
	// Release any retained subviews of the main view.
	//self.myOutlet = nil ;
}


- (void)dealloc {
	[rootTabBarController release];
    [super dealloc];
}

-(void)viewWillAppear:(BOOL)animated{

	serviceLabel.text = @"Service" ; 
	appointmentBefore.text = @"Time" ;
	
	emergenciesButton.alpha = 0.3 ;
	hospitalButton.alpha = 0.3 ;
	gpButton.alpha = 0.3 ;
	dentistButton.alpha = 0.3 ;
	
	min30Button.alpha = 0.3 ;
	min60Button.alpha = 0.3 ;
	min90Button.alpha = 0.3 ; 
	hour24Button.alpha = 0.3 ;
	
		
}

-(void)viewWillDisappear:(BOOL)animated{
	
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
	
}

-(void)alertView:(UIAlertView *) alertView clickedButtonAtIndex: (NSInteger)buttonIndex {
	
	if (buttonIndex == 0){
		//Cancel Button			
		
	}
	
}

@end
