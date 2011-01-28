//
//  GPMapDetailsView.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 23/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "GPMapDetailsView.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import "browserNHSViewGPAppointment.h"
#import <AVFoundation/AVFoundation.h>

@implementation GPMapDetailsView


-(NSString *) dataFilePathGP{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilenameGP ] ;
	
}



// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
    [super viewDidLoad];

	self.title = @"General Practice" ;
	
	//STEP1. Load the information from the Array of the memory:
	
	NSString * filePath = [self dataFilePathGP ] ;
	NSMutableArray * array = [[NSMutableArray alloc] initWithContentsOfFile:filePath] ;
	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSLog(@"Fichero cargado y correcto. Preparado para guardar el nuevo nombre.") ;
	}	
	
	//STEP2. Show the information in the labels:
	
	labelNombre.text    = [array objectAtIndex:0 ] ;
	labelPartner.text   = [array objectAtIndex:1 ] ;
	labelManagerName.text  = [array objectAtIndex:2 ] ;
	labelManagerMail.text   = [array objectAtIndex:3 ] ;	

	
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
	if ([tempoString2 isEqual:tempoString3]) {
		labelAddress1.text = [NSString stringWithFormat:@"%@, %@ %@", tempoString1, tempoString2, tempoString4];
	} else {
		labelAddress1.text = [NSString stringWithFormat:@"%@, %@ %@ %@", tempoString1, tempoString2, tempoString3, tempoString4];
	}
	//labelAddress1.text  = [array objectAtIndex:4 ] ;
	//labelAddress2.text  = [array objectAtIndex:5 ] ;
	
	labelPhone.text     = [array objectAtIndex:8] ; 	
	
	[labelManagerMail release] ;
	[labelPhone release] ;
	
}

-(IBAction)makeAppointmentButton{
	
	//visito pagina intermedia:
	
	//NSString *host = @"www.myoxygen.co.uk";
	//NSString *urlString = [NSString stringWithFormat:@"/pushaps/googleNHS.php"];
	//NSURL *url = [[NSURL alloc] initWithScheme:@"http" host:host path:urlString];

	//NSURLRequest *request = [[NSURLRequest alloc] initWithURL:url];
	//NSData *returnData = [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
	
	
	//browserNHSViewGPAppointment * varbrowserNHSViewGPAppointment = [[browserNHSViewGPAppointment alloc] initWithNibName:@"browserNHSViewGPAppointment" bundle:nil ] ;
	//[[self navigationController] pushViewController:varbrowserNHSViewGPAppointment animated:YES];
	//[varbrowserNHSViewGPAppointment release] ;
	NSString * email = [[NSString alloc] init ] ;
	
	email = @"mailto:" ;
	
	informacion = [[NSString alloc] init] ;
	informacion	= [NSString stringWithFormat:@"Name of the GP Centre: %@. Ubication: %@ %@, %@. Telephone number: %@",labelNombre.text, labelAddress1.text, labelAddress1b.text, labelAddress2.text, labelPhone.text ] ;
	
	informacion = [informacion stringByReplacingOccurrencesOfString:@"(null)" withString:@""] ;
	
	email = [NSString stringWithFormat: @"%@?Subject=NHS Yorkshire and Humber: GP Centre informacion&body=%@", email, (@"%@", informacion)] ; 
	
	email = [email stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
	[[UIApplication sharedApplication] openURL:[NSURL URLWithString:email]] ;
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
