//
//  personalViewSubMenu.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 23/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "personalViewSubMenu.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import "alarmSettingsView.h"
#import "alarmSettingsNEW.h"
#import <AVFoundation/AVAudioPlayer.h>
#import "appointmentsViewController.h"
#import "MyAppointmentsViewController.h"
#import "myNotesViewController.h"
#import <AVFoundation/AVFoundation.h>

#define kFilename @"GeneralData.plist" 

@implementation personalViewSubMenu





-(IBAction)AlarmSettingsButton:(id)sender{
	imagenTermo.hidden = TRUE ;
	
	avanzar = TRUE ;
	//Push the new view

	/*alarmSettingsView * varalarmSettingsView = [[alarmSettingsView alloc] initWithNibName:@"alarmSettingsView" bundle:nil ] ;
	[[self navigationController] pushViewController:varalarmSettingsView animated:YES];
	[varalarmSettingsView release] ;*/

	alarmSettingsNEW * varalarmSettingsNEW = [[alarmSettingsNEW alloc] initWithNibName:@"alarmSettingsNEW" bundle:nil ] ;
	[[self navigationController] pushViewController:varalarmSettingsNEW animated:YES];
	[varalarmSettingsNEW release] ;
}

-(IBAction)MyAppointmentsButton:(id)sender{
	
	avanzar = TRUE ;
	appointmentsViewController * varappointmentsViewController = [[appointmentsViewController alloc] initWithNibName:@"appointmentsViewController" bundle:nil ] ;
	[[self navigationController] pushViewController:varappointmentsViewController animated:YES];
	[varappointmentsViewController release] ;

}

-(IBAction)MyNotesButton:(id)sender{
	
	avanzar = TRUE ;
	myNotesViewController * varmyNotesViewController = [[myNotesViewController alloc] initWithNibName:@"myNotesViewController" bundle:nil ] ;
	[[self navigationController] pushViewController:varmyNotesViewController animated:YES];
	[varmyNotesViewController release] ;
	
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

-(void)viewWillAppear:(BOOL)animated{
	self.title = @"Personal" ;
	avanzar = FALSE ;
}



-(void)viewWillDisappear:(BOOL)animated{

	if(avanzar){
		self.title = @"Back" ;
	}
	
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = @"Personal" ;
	avanzar = FALSE ;

	
//	[ imageView removeFromSuperview] ;
	//[view2 removeFromSuperview]	;
	//[imageView removeFromSuperview ]; 
	//[window addSubview:imageView ]; 
	
	//valorA = 7 ;
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
	// e.g. self.myOutlet = nil;
}


- (void)dealloc {
    [super dealloc];
}


@end
