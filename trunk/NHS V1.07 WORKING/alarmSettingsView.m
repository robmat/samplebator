//
//  alarmSettingsView.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 24/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "alarmSettingsView.h"
#import <AVFoundation/AVAudioPlayer.h>

#import "PushViewControllerAnimatedAppDelegate.h"


@implementation alarmSettingsView


-(NSString *) dataFilePath{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilename ] ;
	
}


-(IBAction)goBackButton:(id)sender{
	
	//Saving the information
	NSMutableArray * array = [[NSMutableArray alloc] init] ;
	
	
	[array addObject:nameField.text ] ;
	[array addObject:dobField.text ] ;
	[array addObject:nextkinField.text ] ;
	[array addObject:bloodField.text ] ;
	
	[array writeToFile:[ self dataFilePath ] atomically:YES ] ;
	
	[array release] ;
	
	
	
	
	
	//Changing the view
	[UIView beginAnimations:nil context:NULL];
	[UIView setAnimationDuration:1.0];
	[UIView setAnimationTransition:UIViewAnimationTransitionFlipFromLeft forView:view1 cache:YES];
	
	[UIView setAnimationTransition:UIViewAnimationTransitionCurlDown forView:view1 cache:YES];
	
	[view2 removeFromSuperview] ;
	
	[UIView commitAnimations];
	
	
	
	
	//Show the information
	
	NSString * filePath = [self dataFilePath ] ;
	
	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		
		NSArray * array = [[NSArray alloc] initWithContentsOfFile:filePath] ;
		nameLabel.text = [array objectAtIndex:0] ;
		dobLabel.text = [array objectAtIndex:1] ;
		nextkinLabel.text = [array objectAtIndex:2] ;
		bloodLabel.text = [array objectAtIndex:3] ;
		[array release] ;
	}	
	
}

-(IBAction)TestButton:(id)sender{
	
	//Loading the Customers General data 
	
	NSString * filePath = [self dataFilePath ] ;
	
	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		
		NSArray * array = [[NSArray alloc] initWithContentsOfFile:filePath] ;
		nameField.text = [array objectAtIndex:0] ;
		dobField.text = [array objectAtIndex:1] ;
		nextkinField.text = [array objectAtIndex:2] ;
		bloodField.text = [array objectAtIndex:3] ;
		[array release] ;
	}
	


	
	
	
	
	// Changing view
	[UIView beginAnimations:nil context:NULL];
	[UIView setAnimationDuration:1.0];
	[UIView setAnimationTransition:UIViewAnimationTransitionFlipFromRight forView:view1 cache:YES];
	
	[UIView setAnimationTransition:UIViewAnimationTransitionCurlUp forView:view1 cache:YES];
	
	[view1 addSubview:view2 ];
	
	[UIView commitAnimations];
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
	nameField.delegate = self ;
	dobField.delegate = self ;
	nextkinField.delegate = self ;
	bloodField.delegate = self ;
	
	NSString * filePath = [self dataFilePath ] ;
	
	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		
		NSArray * array = [[NSArray alloc] initWithContentsOfFile:filePath] ;
		nameLabel.text = [array objectAtIndex:0] ;
		dobLabel.text = [array objectAtIndex:1] ;
		nextkinLabel.text = [array objectAtIndex:2] ;
		bloodLabel.text = [array objectAtIndex:3] ;
		[array release] ;
	}	
	
	[super viewDidLoad];
}


/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

-(BOOL)textFieldShouldReturn:(UITextField *)textField{
	[textField resignFirstResponder] ;
	return YES ;
	
}


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
    
	[nameField release] ;
	[dobField release] ;
	[bloodField release] ;
	[nextkinField release] ;
	
	[super dealloc];
	
}

-(void)viewWillDisappear:(BOOL)animated{
	
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
}

@end
