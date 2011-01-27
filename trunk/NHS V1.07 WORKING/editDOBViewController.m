//
//  editDOBViewController.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 09/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "editDOBViewController.h"
#import "PushViewControllerAnimatedAppDelegate.h"
#import <AVFoundation/AVFoundation.h>


@implementation editDOBViewController

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
	
	[array replaceObjectAtIndex:8 withObject: dateLabel.text ] ;
	
	[array writeToFile:[ self dataFilePath ] atomically:YES ] ;
	
	[array release] ;
	
	NSLog(@"Nuevo DOB guardado") ;
	
	[[self navigationController] popViewControllerAnimated: YES ] ;
	
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	self.title = @"DOB Settings" ;
	//Creo el boton para salvar la informacion
	UIBarButtonItem * saveButton = [[[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"Save", @"")	style: UIBarButtonItemStyleBordered target:self action:@selector(saveAction:)] autorelease ] ;
	self.navigationItem.rightBarButtonItem = saveButton ;
	
	//Asigno el formato de fecha con el que vamos a trabajar
	NSDateFormatter *dateFormat = [[NSDateFormatter alloc] init];
	[dateFormat setDateFormat:@"dd MMMM yyyy"];
	
	
	datePicker.datePickerMode = UIDatePickerModeDate;

	//NSString * tempoDate = [NSString alloc];
	NSDate * todayPICKER = [[NSDate alloc]init] ;
	
	//AQUI VOY A CARGAR LA FECHA YA ALMACENADA:
	
	NSString * filePath = [self dataFilePath ] ;
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {

		NSMutableArray * array = [[NSMutableArray alloc] initWithContentsOfFile:filePath] ;
		dateLabel.text = [array objectAtIndex:8] ;
		NSLog(@"TempoDate: %@",dateLabel.text) ;
		//[array release] ;
	}
	
	


	if ([dateLabel.text isEqualToString:@"Your date of birth"]) {
		
	} 
	else{
		todayPICKER = [dateFormat dateFromString:dateLabel.text ] ;
		datePicker.date = todayPICKER ;
		dateLabel.text = [dateFormat stringFromDate:datePicker.date ];
		
	}
	//Initilation code
	[datePicker addTarget:self action:@selector (changeDataInLabel:) forControlEvents:UIControlEventValueChanged] ;
	[datePicker release] ;	
	
    [super viewDidLoad];

}


- (void)changeDataInLabel:(id)sender{
	
	NSDateFormatter *dateFormat = [[NSDateFormatter alloc] init];
	[dateFormat setTimeZone: [NSTimeZone timeZoneForSecondsFromGMT:0]];
	
	[dateFormat setDateFormat:@"dd MMMM yyyy"];
	
	dateString = [dateFormat stringFromDate:datePicker.date ];
	
	NSLog(@"date: %@", dateString) ;
	dateLabel.text = dateString ;
	[dateFormat release] ;
	
}

/*
-(void) pickerView:(UIPickerView *)thePickerView didSelectRow:(NSInteger)row inComponent:(NSInteger)component{
	
	NSLog(@"Selected item: %@. Index of selected item: %i", [datePicker objectAtIndex:row] , row);
	
	//typeBloodField.text = [listBlood objectAtIndex:row] ;
}*/


/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

-(void)viewWillDisappear:(BOOL)animated{
	
	NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
	AVAudioPlayer* theAudio = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
	[theAudio play];
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


@end
