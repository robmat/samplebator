//
//  NewNotificationTableViewController.m
//  bcm_ip
//
//  Created by User on 3/23/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "NewNotificationTableViewController.h"
#import "ELCTextFieldCell.h"

@implementation NewNotificationTableViewController

@synthesize toolbarOutlet, tableViewOutlet;

#pragma mark -
#pragma mark Initialization

/*
- (id)initWithStyle:(UITableViewStyle)style {
    // Override initWithStyle: if you create the controller programmatically and want to perform customization that is not appropriate for viewDidLoad.
    if ((self = [super initWithStyle:style])) {
    }
    return self;
}
*/


#pragma mark -
#pragma mark View lifecycle


- (void)viewDidLoad {
    [super viewDidLoad];
	self.tableView = tableViewOutlet;
}


/*
- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
}
*/
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


#pragma mark -
#pragma mark Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    // Return the number of sections.
    return 5;
}


- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    if (section == 0) {
		return 3;
	}
	if (section == 1) {
		return 3;
	}
	if (section == 2) {
		return 5;
	}
	if (section == 3) {
		return 2;
	}
	if (section == 4) {
		return 2;
	}
    return 0;
}


// Customize the appearance of table view cells.
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	ELCTextfieldCell* cell = nil;
	if (indexPath.row == 0 && indexPath.section == 0) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: NO] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"incidentNameLbl", nil);
		cell.rightTextField.text = incidentName;
		cell.indexPath = indexPath;
		cell.delegate = self;
	} 
	if (indexPath.row == 1 && indexPath.section == 0) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: NO] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"messageContentLbl", nil);
		cell.rightTextField.text = incidentName;
		cell.indexPath = indexPath;
		cell.delegate = self;
	}
	if (indexPath.row == 2 && indexPath.section == 0) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: YES] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"voiceIntroLbl", nil);
		cell.uiSwitch.on = voiceIntro;
		cell.indexPath = indexPath;
		cell.delegate = self;
	}
	if (indexPath.row == 0 && indexPath.section == 1) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: YES] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"voiceLbl", nil);
		cell.uiSwitch.on = voice;
		cell.indexPath = indexPath;
		cell.delegate = self;
	}
	if (indexPath.row == 1 && indexPath.section == 1) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: YES] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"smsLbl", nil);
		cell.uiSwitch.on = sms;
		cell.indexPath = indexPath;
		cell.delegate = self;
	}
	if (indexPath.row == 2 && indexPath.section == 1) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: YES] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"emailLbl", nil);
		cell.uiSwitch.on = email;
		cell.indexPath = indexPath;
		cell.delegate = self;
	}
	if (indexPath.row == 0 && indexPath.section == 2) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: NO] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"callOpt1Lbl", nil);
		cell.rightTextField.text = callOpt1;
		cell.indexPath = indexPath;
		cell.delegate = self;
	}
	if (indexPath.row == 1 && indexPath.section == 2) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: NO] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"callOpt2Lbl", nil);
		cell.rightTextField.text = callOpt2;
		cell.indexPath = indexPath;
		cell.delegate = self;
	}
	if (indexPath.row == 2 && indexPath.section == 2) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: NO] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"callOpt3Lbl", nil);
		cell.rightTextField.text = callOpt3;
		cell.indexPath = indexPath;
		cell.delegate = self;
	}
	if (indexPath.row == 3 && indexPath.section == 2) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: NO] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"callOpt4Lbl", nil);
		cell.rightTextField.text = callOpt4;
		cell.indexPath = indexPath;
		cell.delegate = self;
	}
	if (indexPath.row == 4 && indexPath.section == 2) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: NO] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"callOpt5Lbl", nil);
		cell.rightTextField.text = callOpt5;
		cell.indexPath = indexPath;
		cell.delegate = self;
	}
	if (indexPath.row == 0 && indexPath.section == 3) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: YES] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"isPersonalizedLbl", nil);
		cell.uiSwitch.on = isPersonalized;
		cell.indexPath = indexPath;
		cell.delegate = self;
	}
	if (indexPath.row == 1 && indexPath.section == 3) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: YES] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"requiresPinLbl", nil);
		cell.uiSwitch.on = requiresPin;
		cell.indexPath = indexPath;
		cell.delegate = self;
	}
	if (indexPath.row == 0 && indexPath.section == 4) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: NO] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"free1Lbl", nil);
		cell.rightTextField.text = free1;
		cell.indexPath = indexPath;
		cell.delegate = self;
	}
	if (indexPath.row == 1 && indexPath.section == 4) {
		cell = [[[ELCTextfieldCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"cell" switchable: NO] autorelease];
		cell.leftLabel.text = NSLocalizedString(@"free2Lbl", nil);
		cell.rightTextField.text = free2;
		cell.indexPath = indexPath;
		cell.delegate = self;
	}
    return cell;
}

-(void)textFieldDidReturnWithIndexPath:(NSIndexPath*)indexPath {
	
	if(indexPath.row < 0) {
		NSIndexPath *path = [NSIndexPath indexPathForRow:indexPath.row+1 inSection:indexPath.section];
		[[(ELCTextfieldCell*)[self.tableView cellForRowAtIndexPath:path] rightTextField] becomeFirstResponder];
		[self.tableView scrollToRowAtIndexPath:path atScrollPosition:UITableViewScrollPositionTop animated:YES];
	}
	
	else {
		
		[[(ELCTextfieldCell*)[self.tableView cellForRowAtIndexPath:indexPath] rightTextField] resignFirstResponder];
	}
}

- (void)updateTextLabelAtIndexPath:(NSIndexPath*)indexPath string:(NSString*)string {
	
	NSLog(@"See input: %@ from section: %d row: %d, should update models appropriately", string, indexPath.section, indexPath.row);
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
        [tableView deleteRowsAtIndexPaths:[NSArray arrayWithObject:indexPath] withRowAnimation:UITableViewRowAnimationFade];
    }   
    else if (editingStyle == UITableViewCellEditingStyleInsert) {
        // Create a new instance of the appropriate class, insert it into the array, and add a new row to the table view
    }   
}
*/


/*
// Override to support rearranging the table view.
- (void)tableView:(UITableView *)tableView moveRowAtIndexPath:(NSIndexPath *)fromIndexPath toIndexPath:(NSIndexPath *)toIndexPath {
}
*/


/*
// Override to support conditional rearranging of the table view.
- (BOOL)tableView:(UITableView *)tableView canMoveRowAtIndexPath:(NSIndexPath *)indexPath {
    // Return NO if you do not want the item to be re-orderable.
    return YES;
}
*/


#pragma mark -
#pragma mark Table view delegate

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    // Navigation logic may go here. Create and push another view controller.
	/*
	 <#DetailViewController#> *detailViewController = [[<#DetailViewController#> alloc] initWithNibName:@"<#Nib name#>" bundle:nil];
     // ...
     // Pass the selected object to the new view controller.
	 [self.navigationController pushViewController:detailViewController animated:YES];
	 [detailViewController release];
	 */
}


#pragma mark -
#pragma mark Memory management

- (void)didReceiveMemoryWarning {
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Relinquish ownership any cached data, images, etc that aren't in use.
}

- (void)viewDidUnload {
    // Relinquish ownership of anything that can be recreated in viewDidLoad or on demand.
    // For example: self.myOutlet = nil;
}


- (void)dealloc {
	[tableViewOutlet release];
	[toolbarOutlet release];
	[incidentName release];
	[messagecontent release];
	[callOpt1 release];
	[callOpt2 release];
	[callOpt3 release];
	[callOpt4 release];
	[callOpt5 release];
	[free1 release];
	[free2 release];
    [super dealloc];
}


@end

