//
//  helpMeView.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 22/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "helpMeView.h"
#import "viewTest.h"
#import<AVFoundation/AVAudioPlayer.h>


NSString *path ;
AVAudioPlayer* theAudio ;



@implementation helpMeView

//@synthesize viewTestView ;

-(IBAction)goBackButton:(id)sender{
	[UIView beginAnimations:nil context:NULL];
	[UIView setAnimationDuration:1.0];
	[UIView setAnimationTransition:UIViewAnimationTransitionFlipFromLeft forView:view1 cache:YES];

		[UIView setAnimationTransition:UIViewAnimationTransitionCurlDown forView:view1 cache:YES];
	
	[view2 removeFromSuperview] ;

	[UIView commitAnimations];
 
}

-(IBAction)TestButton:(id)sender{

	[UIView beginAnimations:nil context:NULL];
	[UIView setAnimationDuration:1.0];
	[UIView setAnimationTransition:UIViewAnimationTransitionFlipFromRight forView:view1 cache:YES];

		[UIView setAnimationTransition:UIViewAnimationTransitionFlipFromRight forView:view1 cache:YES];
	
	
	
	[view1 addSubview:view2 ];
		
	[UIView commitAnimations];
	
	
}

-(IBAction)SOSPhoneButton:(id)sender{
	[[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"tel://07796315464" ]] ;

	//<a href="sms:">Launch text Application</a>
	//<a href="sms:07548184005">New Sms</a>
	
	//sms
	
}

-(IBAction)SOSSMSButton:(id)sender{
	[[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"sms://07548184005" ]] ; 

}


-(IBAction)SOSMailButton:(id)sender{
	[[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"mailto://leopoldo.romacho@gmail.com" ]] ; 
	
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

		
//	NSString *path = [[NSBundle mainBundle] pathForResource:@"Alarm1" ofType:@"mp3"];
//	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];

	
	path = [[NSBundle mainBundle] pathForResource:@"Alarm1" ofType:@"mp3"];
	theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];

	[theAudio play];

		
		

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

-(void)viewWillDisappear:(BOOL)animated{

	[theAudio stop];

	//Button Sound
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
}	



@end
