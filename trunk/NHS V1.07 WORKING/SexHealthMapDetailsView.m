//
//  SexHealthMapDetailsView.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 10/08/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "SexHealthMapDetailsView.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import <AVFoundation/AVFoundation.h>

@implementation SexHealthMapDetailsView

@synthesize openingTimes;

-(NSString *) dataFilePathSex{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilenameSex] ;
}

-(IBAction)showOpeningTimes:(id)sender{
	
	UIAlertView * alert =	[[UIAlertView alloc] initWithTitle:@"Opening Times" message:self.openingTimes delegate:self cancelButtonTitle:@"Ok" otherButtonTitles:nil,  nil];
	[alert show];
	[alert release];
}

-(IBAction)sendMail:(id)sender{
	
	NSString * email = [[NSString alloc] init ] ;
	
	email = @"mailto:" ;
	
	informacion = [[NSString alloc] init] ;
	informacion	= [NSString stringWithFormat:@"Name of the Health Clinic: %@. \n Address: %@ %@, %@. \n Telephone number: %@",labelNombre.text, labelAddress1.text, labelAddress1b.text, labelAddress2.text, labelPhone.text ] ;
	
	informacion = [informacion stringByReplacingOccurrencesOfString:@"(null)" withString:@""] ;
	
	email = [NSString stringWithFormat: @"%@?Subject=NHS Yorkshire and Humber: Details of centre&body=%@", email, (@"%@", informacion)] ; 
	
	email = [email stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
	[[UIApplication sharedApplication] openURL:[NSURL URLWithString:email]] ;
	
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
    [super viewDidLoad];
	
	self.title = @"Sexual Health" ;
	
	//STEP1. Load the information from the Array of the memory:
	
	
	NSString * filePath = [self dataFilePathSex] ;
	NSMutableArray * array = [[NSMutableArray alloc] initWithContentsOfFile:filePath] ;
	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSLog(@"Fichero cargado y correcto. Preparado para guardar el nuevo nombre.") ;
	}	
	
	//STEP2. Show the information in the labels:
	
	labelNombre.text = [array objectAtIndex:0 ] ;
	
	NSString * tempoString1 = [array objectAtIndex:4];
	NSString * tempoString2 = [array objectAtIndex:5];
	
	NSString * tempoString3 = [array objectAtIndex:6];
	NSString * tempoString4 = [array objectAtIndex:7];
	
	labelAddress1.text = [NSString stringWithFormat:@"%@, %@, %@, %@", tempoString1, tempoString2, tempoString4, tempoString3] ;
	
	//informacion = [[NSString alloc] init] ;
	//informacion	= [NSString stringWithFormat:@"Name of the Emergency: %@. Ubication: %@, %@",labelNombre.text, labelAddress1.text, labelAddress2.text ] ;
	
	//informacion = [informacion stringByReplacingOccurrencesOfString:@"(null)" withString:@""] ;
	
	//NSLog(@"%@", informacion) ;
	
	labelPhone.text = [array objectAtIndex:8] ; 
	
	self.openingTimes = [NSString stringWithString:[array objectAtIndex:9]];
	[labelPhone release] ;
	
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
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
	
}


@end
