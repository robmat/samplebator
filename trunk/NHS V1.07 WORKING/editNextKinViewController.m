//
//  editNextKinViewController.m
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 26/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import "editNextKinViewController.h"
#import <AVFoundation/AVFoundation.h>



@implementation editNextKinViewController

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
	
	[array replaceObjectAtIndex:5 withObject: name.text ] ;
	[array replaceObjectAtIndex:6 withObject: phoneNo.text ] ;	
	[array replaceObjectAtIndex:7 withObject: email.text ] ;	
	
	[array writeToFile:[ self dataFilePath ] atomically:YES ] ;
	
	[array release] ;
	
	NSLog(@"Nuevo nombre guardado") ;
	
	[[self navigationController] popViewControllerAnimated: YES ] ;
	
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = @"Next Kin Settings" ;
	UIBarButtonItem * saveButton = [[[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"Save", @"")	style: UIBarButtonItemStyleBordered target:self action:@selector(saveAction:)] autorelease ] ;
	self.navigationItem.rightBarButtonItem = saveButton ;
	
	NSString * filePath = [self dataFilePath ] ;
	NSMutableArray * array = [[NSMutableArray alloc] initWithContentsOfFile:filePath] ;
	
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath]) {
		NSLog(@"Fichero cargado y correcto. Preparado para guardar el nuevo nombre.") ;
	}	
	
	name.text = [array objectAtIndex:5] ;
	phoneNo.text = [array objectAtIndex:6] ;
	email.text = [array objectAtIndex:7] ;
}


/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

-(IBAction)chooseContacts {
    // creating the picker
    if(!picker){
        picker = [[ABPeoplePickerNavigationController alloc] init];
        // place the delegate of the picker to the controll
        picker.peoplePickerDelegate = self;
    }
    // showing the picker
    [self presentModalViewController:picker animated:YES];
}

/**************  Contacts Delegate Functions  ***************/


- (BOOL)peoplePickerNavigationController: (ABPeoplePickerNavigationController *)peoplePicker shouldContinueAfterSelectingPerson:(ABRecordRef)person {
	ABMutableMultiValueRef phoneMulti = ABRecordCopyValue(person, kABPersonPhoneProperty);
	NSMutableArray *phones = [[NSMutableArray alloc] init];
	int i;
	for (i = 0; i < ABMultiValueGetCount(phoneMulti); i++) {
		NSString *aPhone = [(NSString*)ABMultiValueCopyValueAtIndex(phoneMulti, i) autorelease];
		//	NSLog(@"PhoneLabel : %@ & Phone# : %@",aLabel,aPhone);
		[phones addObject:aPhone];
	}
	
	if([phones count] > 0)
	{
		NSString *mobileNo = [phones objectAtIndex:0];
		phoneNo.text = mobileNo;
		NSLog(mobileNo);
	}
	else{
		phoneNo.text = @"" ;
	}
	

	name.text = (NSString*)ABRecordCopyCompositeName(person);
	
	ABMutableMultiValueRef emailMulti = ABRecordCopyValue(person, kABPersonEmailProperty);
	NSMutableArray *emails = [[NSMutableArray alloc] init];
	for (i = 0; i < ABMultiValueGetCount(emailMulti); i++) {
		NSString *anEmail = [(NSString*)ABMultiValueCopyValueAtIndex(emailMulti, i) autorelease];
		[emails addObject:anEmail];
	}
	
	if([emails count] > 0)
	{
		NSString *emailAdress = [emails objectAtIndex:0];
		
		email.text = emailAdress;
		NSLog(emailAdress);
	}
	else{
		email.text = @"" ;
	}
	[peoplePicker dismissModalViewControllerAnimated:YES];
	
	NSLog(@"Cargado correctamente!") ;
    return YES;
	

}

- (BOOL)peoplePickerNavigationController: (ABPeoplePickerNavigationController *)peoplePicker
      shouldContinueAfterSelectingPerson:(ABRecordRef)person
                                property:(ABPropertyID)property
                              identifier:(ABMultiValueIdentifier)identifier{
	
	
    return NO;
	
	
}

- (void)peoplePickerNavigationControllerDidCancel:(ABPeoplePickerNavigationController *)peoplePicker {
    // assigning control back to the main controller
    [picker dismissModalViewControllerAnimated:YES];
}

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
