//
//  myNotesViewController.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 11/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "myNotesViewController.h"
#import <AVFoundation/AVAudioPlayer.h>
#import "PushViewControllerAnimatedAppDelegate.h"
#import "customCell.h"

@implementation myNotesViewController

-(NSString *) dataFilePath{
	
	NSArray * paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	NSString * documentsDirectory = [paths objectAtIndex:0] ;
	return [documentsDirectory stringByAppendingPathComponent:kFilename ] ;
	
}


- (void)viewDidLoad {
    [super viewDidLoad];
	
	UIBarButtonItem * saveButton = [[[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"Save", @"")	style: UIBarButtonItemStyleBordered target:self action:@selector(saveAction:)] autorelease ] ;
	self.navigationItem.rightBarButtonItem = saveButton ;
	
	capitals = [[NSMutableArray alloc] initWithObjects: @"Name",@"DOB", @"Adress",@"Blood",@"Allergies",@"Medication",@"Next of kin", nil ] ;
	states = [[NSMutableArray alloc] initWithObjects: @"Your name",@"Your DOB", @"Your adress",@"Your blood", @"Your allergies", @"Your medication", @"Kin information", nil ] ; 			   
	
	
	capitals = [[NSMutableArray alloc] initWithObjects: @"Name",@"DOB", @"Adress",@"Blood",@"Allergies",@"Medication",@"Existing conditions",@"Next of kin", nil ] ;
	states = [[NSMutableArray alloc] initWithObjects: @"Your name",@"Your DOB", @"Your adress",@"Your blood", @"Your allergies", @"Your medication", @"Your existing conditions" , @"Kin information", nil ] ; 			   
	
	myTableView.rowHeight = 64.0 ;
	
	NSString * filePath = [self dataFilePath ] ;
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSArray * array = [[NSArray alloc] initWithContentsOfFile:filePath] ;
		//Cargo el nombre en el indice cero (recordemos que states es la informacion del usuario)
		[states replaceObjectAtIndex:0 withObject:   [array objectAtIndex:0]  ] ;   // Name
		[states replaceObjectAtIndex:1 withObject:   [array objectAtIndex:8]  ] ;   // DOB	
		[states replaceObjectAtIndex:2 withObject:   [array objectAtIndex:1]  ] ;   // Adress
		[states replaceObjectAtIndex:3 withObject:   [array objectAtIndex:2]  ] ;   // Blood type
		[states replaceObjectAtIndex:4 withObject:   [array objectAtIndex:9]  ] ;   // ALERGIES	
		[states replaceObjectAtIndex:5 withObject:   [array objectAtIndex:10] ] ;   // MEDICATION	
		[states replaceObjectAtIndex:6 withObject:   [array objectAtIndex:13] ] ;   // Existing conditions
		[states replaceObjectAtIndex:7 withObject:   [array objectAtIndex:5]  ] ;   // Next of kin name
		
		mytextNote.text = [array objectAtIndex:12] ; // Muestro mis notas en la pantalla.
		
		[array release] ;
		[myTableView reloadData] ;
	}
	
	self.title = @"My notes" ;
}

// called when keyboard SEARCH button pressed
-(IBAction)saveAction:(id)sender{
	
	
	NSLog(@"Done pressed") ;
	
	
	//STEP1. Load the information from the Array of the memory:
	
	NSString * filePath = [self dataFilePath ] ;
	NSMutableArray * array = [[NSMutableArray alloc] initWithContentsOfFile:filePath] ;
	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSLog(@"Fichero cargado y correcto. Preparado para guardar el nuevo nombre.") ;
	}	
	
	
	//STEP2. Save the information: 
	
	[array replaceObjectAtIndex:12 withObject: mytextNote.text ] ;
	
	[array writeToFile:[ self dataFilePath ] atomically:YES ] ;
	
	[array release] ;
	
	NSLog(@"Nuevo nombre guardado") ;
	
	[mytextNote resignFirstResponder] ;
	
	//[[self navigationController] popViewControllerAnimated: YES ] ;	
	
	
}

-(IBAction)	buttonMedication:(id)sender{
	[self.view addSubview:vistaNewView ] ;
	
	mainText.text = @"Medication" ;
	insideText.text = [states objectAtIndex:5] ;
	
}

-(IBAction)	buttonAllergies:(id)sender{
	
	NSLog(@"Allergies pressed!") ;
	
	[self.view addSubview:vistaNewView ] ;
	
	mainText.text = @"  Allergies" ;
	insideText.text = [states objectAtIndex:4] ;
	
}


-(IBAction)	buttonConditions:(id)sender{
	
	NSLog(@"Conditions pressed!") ;
	
	[self.view addSubview:vistaNewView ] ;
	
	mainText.text = @"Conditions" ;
	insideText.text = [states objectAtIndex:6] ;
	
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
    
    static NSString *CellIdentifier = @"CustomCell";
	
    CustomCell *cell = (CustomCell *) [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
	
    
	if (cell == nil) {
		
		NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"CustomCell" owner:self options:nil];
		
		for (id currentObject in topLevelObjects){
			if ([currentObject isKindOfClass:[UITableViewCell class]]){
				if(cell.textLabel.text != @"Alarm sound"){
					cell =  (CustomCell *) currentObject;
					cell.detailButton.alpha = 0 ;				
					break;
				}	
			}
		}
	}
	
	cell.autoresizesSubviews = YES ;
	
	
	
	
	
	//Carga los datos en los arrays de la pantalla
	cell.capitalLabel.text = [capitals objectAtIndex:indexPath.row];
	cell.stateLabel.text = [states objectAtIndex:indexPath.row];
	cell.detailButtonRound.alpha = 0 ;
	
	if ([cell.capitalLabel.text isEqualToString:@"Medication"]) {
		[cell.detailButtonRound addTarget:self action: @selector(buttonMedication:) forControlEvents:UIControlEventTouchUpInside ] ;
		
 		cell.detailButton.alpha = 0 ;
		cell.detailButtonRound.alpha = 1 ;
		
	}
	
	if ([cell.capitalLabel.text isEqualToString:@"Allergies"]) {
		[cell.detailButtonRound addTarget:self action: @selector(buttonAllergies:) forControlEvents:UIControlEventTouchUpInside ] ;
		cell.detailButton.alpha = 0 ;
		cell.detailButtonRound.alpha = 1 ;
	}
	
	if ([cell.capitalLabel.text isEqualToString:@"Existing conditions"]) {
		[cell.detailButtonRound addTarget:self action: @selector(buttonConditions:) forControlEvents:UIControlEventTouchUpInside ] ;
		cell.detailButton.alpha = 0 ;
		cell.detailButtonRound.alpha = 1 ;
	}	
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

-(IBAction)closeNewView{
	[vistaNewView removeFromSuperview ] ;
	
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	
	NSString * str = [ capitals  objectAtIndex:indexPath.row ] ;
	
	if ( [str isEqual:@"Allergies" ] ) {
		
		
		
	}	
	if ( [str isEqual:@"Medication" ] ) {
		
		
	}	
	
}


-(void)viewWillDisappear:(BOOL)animated{
	
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
}

@end
