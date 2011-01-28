//
//  alarmSettingsNEW.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 09/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "alarmSettingsNEW.h"
#import "customCell.h"

#import "editNameViewController.h"
#import "editBloodViewController.h"
#import "editDOBViewController.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import "editAdressViewController.h"
#import "donorViewController.h"
#import "View1Controller.h"
#import "editNextKinViewController.h"
#import "editAllergiesViewController.h"
#import "editMedicationViewController.h"
#import "editexistingConditionsController.h"
#import <AVFoundation/AVFoundation.h>

@implementation alarmSettingsNEW

@synthesize lista ;


NSString *CellIdentifier = @"CustomCell";


-(NSString *) dataFilePath{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilename ] ;
	
}

-(IBAction)loadButton:(id)sender{


}

/*
- (id)initWithStyle:(UITableViewStyle)style {
    // Override initWithStyle: if you create the controller programmatically and want to perform customization that is not appropriate for viewDidLoad.
    if (self = [super initWithStyle:style]) {
    }
    return self;
}
*/


- (void)viewDidLoad {
    [super viewDidLoad];
	avanzar = FALSE ;

	self.title = @"Emergency Settings" ;
	
	//Informacion por defecto de los arrays
    capitals = [[NSMutableArray alloc] initWithObjects: @"Name",@"DOB", @"Address",@"Blood",@"Allergies",@"Medication",@"Existing Conditions", @"Next of kin",@"Alarm sound", nil ] ;
	states = [[NSMutableArray alloc] initWithObjects: @"Your name",@"Your DOB", @"Your adress",@"Your blood", @"Your allergies", @"Your medication", @"Your existing conditions", @"Kin information", @"Off", nil ] ; 			   
	
	myTableView.rowHeight = 70.0 ;
}

- (void)viewWillAppear:(BOOL)animated {
	self.title = @"Emergency Settings" ; 
	// READING INFORMATION FROM THE MEMORY:
	
	
	//Load the information from the Array of the memory	
	NSString * filePath = [self dataFilePath ] ;
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSArray * array = [[NSArray alloc] initWithContentsOfFile:filePath] ;
		//Cargo el nombre en el indice cero (recordemos que states es la informacion del usuario)
		[states replaceObjectAtIndex:0 withObject:   [array objectAtIndex:0]  ]  ; // Name
		[states replaceObjectAtIndex:1 withObject:   [array objectAtIndex:8]  ]  ; // DOB	
		[states replaceObjectAtIndex:2 withObject:   [array objectAtIndex:1]  ]  ; // Adress
		[states replaceObjectAtIndex:3 withObject:   [array objectAtIndex:2]  ]  ; // Blood type
		[states replaceObjectAtIndex:4 withObject:   [array objectAtIndex:9]  ]  ; // ALERGIES	
		[states replaceObjectAtIndex:5 withObject:   [array objectAtIndex:10]  ] ; // MEDICATION	
		[states replaceObjectAtIndex:6 withObject:   [array objectAtIndex:13]  ] ; // Existing Conditions	
		[states replaceObjectAtIndex:7 withObject:   [array objectAtIndex:5]   ] ; // Next of kin name
		[states replaceObjectAtIndex:8 withObject:   [array objectAtIndex:11]  ] ; // Alarm sound (On or Off)

		//[states replaceObjectAtIndex:9 withObject:   [array objectAtIndex:5]  ] ; // Next of kin mail
		//[states replaceObjectAtIndex:7 withObject:   @"Andrea Verona"  ] ; // Next of kin name


		
		[array release] ;
		[myTableView reloadData] ;
	}	
	
	
	[super viewWillAppear:animated];
}

/*
- (void)viewDidAppear:(BOOL)animated {
    [super viewDidAppear:animated];
}
*/
/*
- (void)viewWillDisappear:(BOOL)animated {
	[super viewWillDisappear:animated];
}
*/
/*
- (void)viewDidDisappear:(BOOL)animated {
	[super viewDidDisappear:animated];
}
*/

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


#pragma mark Table view methods

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}


// Customize the number of rows in the table view.
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [states count];
}


// Customize the appearance of table view cells.
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    //static NSString *CellIdentifier = @"CustomCell";
	
    CustomCell *cell = (CustomCell *) [tableView dequeueReusableCellWithIdentifier:CellIdentifier];

    
	if (cell == nil) {
		
		NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"CustomCell" owner:self options:nil];
		
		for (id currentObject in topLevelObjects){
			if ([currentObject isKindOfClass:[UITableViewCell class]]){
				cell =  (CustomCell *) currentObject;
				break;
			}
		}
	}
	
	cell.autoresizesSubviews = YES ;
	
	
	
	
	
	//Carga los datos en los arrays de la pantalla
	cell.capitalLabel.text = [capitals objectAtIndex:indexPath.row];
	cell.stateLabel.text = [states objectAtIndex:indexPath.row];
	cell.detailButtonRound.alpha = 0 ;
	
	//En state tengo la informacion del usuario
	
	//ceel.stateLabel.text = [states
	
	
	
	
	//cell.capitalLabel.text = [ @"HOOLAA" objectAtIndex:0 ] ;
	
	
	//IMPORTANT::We are going to load the information from the Memory array:
	
		NSString * filePath = [self dataFilePath ] ;
	
	
		if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		
			//NSArray * array = [[NSArray alloc] initWithContentsOfFile:filePath] ;
			//nameLabel.text = [array objectAtIndex:0] ;
			//dobLabel.text = [array objectAtIndex:1] ;
			//nextkinLabel.text = [array objectAtIndex:2] ;
			//bloodLabel.text = [array objectAtIndex:3] ;
			//[array release] ;
		}	
	
	
    return cell;
}



- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {

	NSString * str = [ capitals  objectAtIndex:indexPath.row ] ;
	
	if ( [str isEqual:@"Name" ] ) {
		
		avanzar = TRUE ;
		editNameViewController * vareditNameViewController = [[editNameViewController alloc] initWithNibName:@"editNameViewController" bundle:nil ] ;
		[[self navigationController] pushViewController:vareditNameViewController animated:YES];
		[vareditNameViewController release] ;
	
	}
	if ( [str isEqual:@"DOB" ] ) {
		avanzar = TRUE ;
		editDOBViewController * vareditDOBViewController = [[editDOBViewController alloc] initWithNibName:@"editDOBViewController" bundle:nil ] ;
		[[self navigationController] pushViewController:vareditDOBViewController animated:YES];
		[vareditDOBViewController release] ;		
		
	}
	if ( [str isEqual:@"Blood" ] ) {
		avanzar = TRUE ;		
		editBloodViewController * vareditBloodViewController = [[editBloodViewController alloc] initWithNibName:@"editBloodViewController" bundle:nil ] ;
		[[self navigationController] pushViewController:vareditBloodViewController animated:YES];
		[vareditBloodViewController release] ;
		
	}
	if ( [str isEqual:@"Address" ] ) {
		avanzar = TRUE ;		
		editAdressViewController * vareditAdressViewController = [[editAdressViewController alloc] initWithNibName:@"editAdressViewController" bundle:nil ] ;
		[[self navigationController] pushViewController:vareditAdressViewController animated:YES];
		[vareditAdressViewController release] ;
		
	}	
	
	if ( [str isEqual:@"Next of kin" ] ) {
		avanzar = TRUE ;		
		editNextKinViewController * vareditNextKinViewController = [[editNextKinViewController alloc] initWithNibName:@"editNextKinViewController" bundle:nil ] ;
		[[self navigationController] pushViewController:vareditNextKinViewController animated:YES];
		[vareditNextKinViewController release] ;
		
	}	
	
	if ( [str isEqual:@"Allergies" ] ) {
		avanzar = TRUE ;		
		editAllergiesViewController * vareditAllergiesViewController = [[editAllergiesViewController alloc] initWithNibName:@"editAllergiesViewController" bundle:nil ] ;
		[[self navigationController] pushViewController:vareditAllergiesViewController animated:YES];
		[vareditAllergiesViewController release] ;
		
	}	
	if ( [str isEqual:@"Medication" ] ) {
		avanzar = TRUE ;		
		editMedicationViewController * vareditMedicationViewController = [[editMedicationViewController alloc] initWithNibName:@"editMedicationViewController" bundle:nil ] ;
		[[self navigationController] pushViewController:vareditMedicationViewController animated:YES];
		[vareditMedicationViewController release] ;
		
	}	
	if ( [str isEqual:@"Existing Conditions" ] ) {
		avanzar = TRUE ;		
		editexistingConditionsController * vareditexistingConditionsController = [[editexistingConditionsController alloc] initWithNibName:@"editexistingConditionsController" bundle:nil ] ;
		[[self navigationController] pushViewController:vareditexistingConditionsController animated:YES];
		[vareditexistingConditionsController release] ;
	}		
	if ( [str isEqual:@"Alarm sound" ] ) {
		avanzar = TRUE ;		
		donorViewController * vardonorViewController = [[donorViewController alloc] initWithNibName:@"donorViewController" bundle:nil ] ;
		[[self navigationController] pushViewController:vardonorViewController animated:YES];
		[vardonorViewController release] ;
		
	}	
	
}


/*
// Override to support conditional editing of the table view.
- (BOOL)tableView:(UITableView *)tableView canEditRowAtIndexPath:(NSIndexPath *)indexPath {
    // Return NO if you do not want the specified item to be editable.
    return YES;
}
*/


/*
// Override to support editing the table view.
- (void)tableView:(UITableView *)tableView commitEditingStyle:(UITableViewCellEditingStyle)editingStyle forRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (editingStyle == UITableViewCellEditingStyleDelete) {
        // Delete the row from the data source
        [tableView deleteRowsAtIndexPaths:[NSArray arrayWithObject:indexPath] withRowAnimation:YES];
    }   
    else if (editingStyle == UITableViewCellEditingStyleInsert) {
        // Create a new instance of the appropriate class, insert it into the array, and add a new row to the table view
    }   
}
*/




-(void)viewWillDisappear:(BOOL)animated{
	if(avanzar){
		self.title = @"Back" ;
	}	
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
	
}


- (void)dealloc {
	[lista release] ;
    [super dealloc];
}


@end

