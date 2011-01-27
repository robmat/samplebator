//
//  editAdressViewController.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 10/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "editAdressViewController.h"

#import "PushViewControllerAnimatedAppDelegate.h"
#import <AVFoundation/AVFoundation.h>


@implementation editAdressViewController

-(NSString *) dataFilePath{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilename ] ;
	
}

-(IBAction)saveAction:(id)sender{
	
	
	//STEP1. Load the information from the Array of the memory:
	
	NSString * filePath = [self dataFilePath ] ;
	NSMutableArray * array = [[NSMutableArray alloc] initWithContentsOfFile:filePath] ;
	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSLog(@"Fichero cargado y correcto. Preparado para guardar la nueva direccion.") ;
	}	
	
	
	//STEP2. Save the information: 
	
	[array replaceObjectAtIndex:1 withObject: addressField.text ] ;
	[array writeToFile:[ self dataFilePath ] atomically:YES ] ;
	[array release] ;
	
	NSLog(@"Nueva direccion guardado") ;
	
	[[self navigationController] popViewControllerAnimated: YES ] ;
	
}

- (void)viewDidLoad {
	
	UIBarButtonItem * saveButton = [[[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"Save", @"")	style: UIBarButtonItemStyleBordered target:self action:@selector(saveAction:)] autorelease ] ;
	self.navigationItem.rightBarButtonItem = saveButton ;
	
	self.title = @"Address Settings" ;
	
	NSString * filePath = [self dataFilePath ] ;
	
	
	//Leo el nombre de la memoria	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSMutableArray * array = [[NSMutableArray alloc] initWithContentsOfFile:filePath] ;

		addressField.text = [array objectAtIndex:1] ;

		NSLog(@"%@", array) ;
		[array release] ;
	}	
	else{
		NSLog(@"MENU EDIT ADDRESS: ERROR CON EL FICHERO DE LA MEMORIA! POR LO QUE SEA NO EXISTE O NO PUEDO LEERLO.") ;
	}
	
	if ([addressField.text isEqualToString:@"Your address"]) {
		addressField.text = @"" ;
		addressField.placeholder = @"Street, City, Postcode" ;
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

- (void)didReceiveMemoryWarning {
	// Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
	
	// Release any cached data, images, etc that aren't in use.
}

- (void)viewDidUnload {
	// Release any retained subviews of the main view.
	// e.g. self.myOutlet = nil;
}

- (BOOL)textFieldShouldReturn:(UITextField *)theTextField{
	
	
	//STEP1. Load the information from the Array of the memory:
	
	NSString * filePath = [self dataFilePath ] ;
	NSMutableArray * array = [[NSMutableArray alloc] initWithContentsOfFile:filePath] ;
	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSLog(@"Fichero cargado y correcto. Preparado para guardar la nueva direccion.") ;
	}	
	
	
	//STEP2. Save the information: 
	
	[array replaceObjectAtIndex:1 withObject: addressField.text ] ;
	[array writeToFile:[ self dataFilePath ] atomically:YES ] ;
	[array release] ;
	
	NSLog(@"Nueva direccion guardado") ;
	
	[[self navigationController] popViewControllerAnimated: YES ] ;
	
	return YES ;	
	
	
}

-(void)viewWillDisappear:(BOOL)animated{
	
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
}

- (void)dealloc {
    [super dealloc];
}





@end
