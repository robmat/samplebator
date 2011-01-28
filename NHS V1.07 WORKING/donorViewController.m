//
//  donorViewController.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 12/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "donorViewController.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import <AVFoundation/AVFoundation.h>


@implementation donorViewController

@synthesize donorYESorNo ;

/*
 // The designated initializer.  Override if you create the controller programmatically and want to perform customization that is not appropriate for viewDidLoad.
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    if (self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil]) {
        // Custom initialization
    }
    return self;
}
*/

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
	
	if ( donorYESorNo.isOn ){
		[array replaceObjectAtIndex:11 withObject:   @"On"  ] ;
	}
	else {
		[array replaceObjectAtIndex:11 withObject:   @"Off"  ] ;	
	}
	
	
	[array writeToFile:[ self dataFilePath ] atomically:YES ] ;
	[array release] ;

	//NSLog(@"%@, %@", [array objectAtIndex:3] , [array objectAtIndex:4] ) ;
	
	[[self navigationController] popViewControllerAnimated: YES ] ;
}


-(IBAction)buttonSwitchPressed{
	NSLog(@"Switch pressed") ;

	
	if ( donorYESorNo.isOn ){
		NSLog(@"is onnn") ;
		[ donorYESorNo setOn:YES animated:NO ] ;
		

	}
	else {

		[ donorYESorNo setOn:NO animated:NO ] ;
		numberField.enabled = FALSE ;	
	}

	
	
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	
	self.title = @"Alarm Settings" ;
    
	UIBarButtonItem * saveButton = [[[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"Save", @"")	style: UIBarButtonItemStyleBordered target:self action:@selector(saveAction:)] autorelease ] ;
	self.navigationItem.rightBarButtonItem = saveButton ;
	
	NSString * filePath = [self dataFilePath ] ;

	//Leo el nombre de la memoria	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSMutableArray * array = [[NSMutableArray alloc] initWithContentsOfFile:filePath] ;
		
		NSString * tempoString = [[NSString alloc] init] ;

		tempoString = [array objectAtIndex:11] ;
		
		if( [tempoString isEqualToString:@"Off"]){
			NSLog(@"La alarma esta desactivada") ;
			[ donorYESorNo setOn:NO animated:NO ] ;
			}
		else{
			NSLog(@"La alarma esta activiada") ;
			[ donorYESorNo setOn:YES animated:NO ] ;
			}
		
		[array release] ;
	}	
	else{
		NSLog(@"MENU EDIT DONOR: ERROR CON EL FICHERO DE LA MEMORIA! POR LO QUE SEA NO EXISTE O NO PUEDO LEERLO.") ;
	}

	
	
	
	
	
	[super viewDidLoad];


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
    [super dealloc];
}

-(void)viewWillDisappear:(BOOL)animated{
	
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
}

@end
