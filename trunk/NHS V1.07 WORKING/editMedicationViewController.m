//
//  editMedicationViewController.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 26/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "editMedicationViewController.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import <AVFoundation/AVFoundation.h>

@implementation editMedicationViewController

//NSMutableArray * array ;


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
		NSLog(@"Fichero cargado y correcto. Preparado para guardar el nuevo nombre.") ;
	}	
	
	
	//STEP2. Save the information: 
	
	[array replaceObjectAtIndex:10 withObject: medicationField.text ] ;
	
	[array writeToFile:[ self dataFilePath ] atomically:YES ] ;
	
	[array release] ;
	
	NSLog(@"Nuevo nombre guardado") ;
	
	[[self navigationController] popViewControllerAnimated: YES ] ;
	
}

-(void)viewDidAppear:(BOOL)animated{
	
	[medicationField becomeFirstResponder] ;
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

/*
// called when keyboard SEARCH button pressed
- (BOOL)textFieldShouldReturn:(UITextField *)theTextField{
	
	
	NSLog(@"Done pressed") ;
	
	[theTextField resignFirstResponder] ;
	
	//STEP1. Load the information from the Array of the memory:
	
	NSString * filePath = [self dataFilePath ] ;
	NSMutableArray * array = [[NSMutableArray alloc] initWithContentsOfFile:filePath] ;
	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSLog(@"Fichero cargado y correcto. Preparado para guardar el nuevo nombre.") ;
	}	
	
	
	//STEP2. Save the information: 
	
	[array replaceObjectAtIndex:10 withObject: medicationField.text ] ;
	
	[array writeToFile:[ self dataFilePath ] atomically:YES ] ;
	
	[array release] ;
	
	NSLog(@"Nuevo nombre guardado") ;
	
	[[self navigationController] popViewControllerAnimated: YES ] ;	
	
	return YES ;	
	
	
}*/

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	
	UIBarButtonItem * saveButton = [[[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"Save", @"")	style: UIBarButtonItemStyleBordered target:self action:@selector(saveAction:)] autorelease ] ;
	self.navigationItem.rightBarButtonItem = saveButton ;
	self.title = @"Medication" ;
	
	NSString * filePath = [self dataFilePath ] ;
	//Leo el nombre de la memoria	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSMutableArray * array = [[NSMutableArray alloc] initWithContentsOfFile:filePath] ;
		medicationField.text = [array objectAtIndex:10] ;
		NSLog(@"%@", array) ;
		[array release] ;
	}	
	else{
		NSLog(@"MENU EDIT NAME: ERROR CON EL FICHERO DE LA MEMORIA! POR LO QUE SEA NO EXISTE O NO PUEDO LEERLO.") ;
	}
	
	if ([medicationField.text isEqualToString:@"Your medication"]) {
		medicationField.text = @"" ;
		//medicationField.placeholder = @"Your medication" ;
	}
	
    [super viewDidLoad];
}

-(void)viewWillDisappear:(BOOL)animated{
	
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
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


@end