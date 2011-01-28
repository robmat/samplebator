//
//  editNameViewController.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 09/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "editNameViewController.h"
#import "editNameViewController.h"
#import "alarmSettingsNEW.h"

#import "PushViewControllerAnimatedAppDelegate.h"
#import <AVFoundation/AVFoundation.h>

@implementation editNameViewController

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
	
	[array replaceObjectAtIndex:0 withObject: nameField.text ] ;
	
	[array writeToFile:[ self dataFilePath ] atomically:YES ] ;
	
	[array release] ;
	
	NSLog(@"Nuevo nombre guardado") ;
	
	[[self navigationController] popViewControllerAnimated: YES ] ;
	
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
	
	[array replaceObjectAtIndex:0 withObject: nameField.text ] ;
	
	[array writeToFile:[ self dataFilePath ] atomically:YES ] ;
	
	[array release] ;
	
	NSLog(@"Nuevo nombre guardado") ;
	
	[[self navigationController] popViewControllerAnimated: YES ] ;	
	
	return YES ;	
	
	
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {

	self.title = @"Name Settings" ;	
	UIBarButtonItem * saveButton = [[[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"Save", @"")	style: UIBarButtonItemStyleBordered target:self action:@selector(saveAction:)] autorelease ] ;
	self.navigationItem.rightBarButtonItem = saveButton ;
	
	
	NSString * filePath = [self dataFilePath ] ;
	//Leo el nombre de la memoria	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSMutableArray * array = [[NSMutableArray alloc] initWithContentsOfFile:filePath] ;
		nameField.text = [array objectAtIndex:0] ;
		NSLog(@"%@", array) ;
		[array release] ;
	}	
	else{
		NSLog(@"MENU EDIT NAME: ERROR CON EL FICHERO DE LA MEMORIA! POR LO QUE SEA NO EXISTE O NO PUEDO LEERLO.") ;
	}
	
	if ([nameField.text isEqualToString:@"Your name"]) {
		nameField.text = @"" ;
		nameField.placeholder = @"Firstname and surname" ;
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

-(void)viewWillDisappear:(BOOL)animated{
	
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
}

- (void)dealloc {
    [super dealloc];
}


@end
