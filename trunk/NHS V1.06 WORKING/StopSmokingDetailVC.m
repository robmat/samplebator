//
//  StopSmokingDetailVC.m
//  PushViewControllerAnimated
//
//  Created by User on 4/29/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "StopSmokingDetailVC.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import <AVFoundation/AVFoundation.h>

@implementation StopSmokingDetailVC

@synthesize openingTimes;

-(NSString *) dataFilePathSex{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilenameSmoking] ;
}

-(IBAction)showOpeningTimes:(id)sender{
	
	UIAlertView * alert =	[[UIAlertView alloc] initWithTitle:@"Opening Times" message:self.openingTimes delegate:self cancelButtonTitle:@"Ok" otherButtonTitles:nil,  nil];
	[alert show];
	[alert release];
}

-(IBAction)sendMail:(id)sender{
	
	NSString * email = [[NSString alloc] init ] ;
	
	email = @"mailto:" ;
	
	NSString* informacion = [[NSString alloc] init] ;
	informacion	= [NSString stringWithFormat:@"Name of the Health Clinic: %@. \n Address: %@ %@, %@. \n Telephone number: %@",labelNombre.text, labelAddress1.text, labelAddress1b.text, labelAddress2.text, labelPhone.text ] ;
	
	informacion = [informacion stringByReplacingOccurrencesOfString:@"(null)" withString:@""] ;
	
	email = [NSString stringWithFormat: @"%@?Subject=NHS Bristol: Details of centre&body=%@", email, (@"%@", informacion)] ; 
	
	email = [email stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
	[[UIApplication sharedApplication] openURL:[NSURL URLWithString:email]] ;
	
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
    [super viewDidLoad];
	
	self.title = @"Stop smoking" ;
	
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
	
	if (tempoString2 || ![tempoString2 isEqual:@""]) {
		tempoString2 = [NSString stringWithFormat:@"%@,", tempoString2];
	}
	if (tempoString3 || ![tempoString3 isEqual:@""]) {
		tempoString3 = [NSString stringWithFormat:@"%@,", tempoString3];
	}
	//if ([tempoString2 isEqual:tempoString3]) {
		labelAddress1.text = [NSString stringWithFormat:@"%@, %@ %@", tempoString1, tempoString2, tempoString4];
	//} else {
	//	labelAddress1.text = [NSString stringWithFormat:@"%@, %@ %@ %@", tempoString1, tempoString2, tempoString3, tempoString4];
	//}
	
	labelPhone.text = [array objectAtIndex:8] ; 
	
	self.openingTimes = [NSString stringWithString:[array objectAtIndex:9]];
	comment.text = self.openingTimes;
	[labelPhone release] ;
	
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}

- (void)viewDidUnload {
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
