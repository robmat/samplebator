//
//  ProcessesViewController.m
//  bcm_ip
//
//  Created by User on 3/10/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "ProcessesViewController.h"
#import "HttpRequestWrapper.h"
#import "TBXML.h"


@implementation ProcessesViewController


#pragma mark -
#pragma mark View lifecycle


- (void)viewDidLoad {
    [super viewDidLoad];
	httpRequest = [[HttpRequestWrapper alloc] initWithDelegate:self];
	[httpRequest makeRequestWithParams:[NSDictionary dictionaryWithObjectsAndKeys: @"getAllProcesses", @"action", nil]];
	self.title = NSLocalizedString(@"processesFormTitle", nil);
	selectedRow = [NSNumber numberWithInt: -1];
    // Uncomment the following line to display an Edit button in the navigation bar for this view controller.
    self.navigationItem.rightBarButtonItem = [[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"refreshBtnLbl", nil) style:UIBarButtonItemStylePlain target:self action: @selector(refreshAction)];
}
- (void) refreshAction {
	[httpRequest release];
	httpRequest = [[HttpRequestWrapper alloc] initWithDelegate:self];
	[httpRequest makeRequestWithParams:[NSDictionary dictionaryWithObjectsAndKeys: @"getAllProcesses", @"action", nil]];
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	NSString* responseString = [request responseString];
	TBXML* xmlDoc = [TBXML tbxmlWithXMLString:responseString];
	itemsArray = [[NSMutableArray alloc] init];
	TBXMLElement* itemElem = [TBXML childElementNamed:@"BusinessProcess" parentElement: xmlDoc.rootXMLElement]; 
	
	do {
		TBXMLElement* itemChildElem = itemElem->firstChild;
		NSMutableDictionary* processDict = [NSMutableDictionary dictionaryWithCapacity:10];
		[itemsArray addObject:processDict];
		
		while (itemChildElem = itemChildElem->nextSibling) {
			NSString* elemName = [TBXML elementName:itemChildElem];
			NSString* elemValu = [TBXML textForElement:itemChildElem];
			[processDict setObject:elemValu forKey:elemName];
		}
		
		itemElem = [TBXML nextSiblingNamed:@"BusinessProcess" searchFromElement:itemElem];
	} while (itemElem);
	[self.tableView reloadData];
}
- (void)requestFailed:(ASIHTTPRequest *)request {
	NSError *error = [request error];
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle: NSLocalizedString(@"errorDialogTitle", nil) message: [error localizedDescription] delegate: nil cancelButtonTitle: @"OK" otherButtonTitles: nil];
	[alert show];
	[alert release];
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
    return 1;
}


- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [itemsArray count];
}


// Customize the appearance of table view cells.
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    static NSString *CellIdentifier = @"Cell";
    
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:CellIdentifier] autorelease];
    }
    if ([selectedRow isEqualToNumber:[NSNumber numberWithInt:indexPath.row]]) {
		[cell.contentView addSubview: [self composeViewForSelectedRow: indexPath cellContentFrame: cell.contentView.frame]];
	} else {
		cell.textLabel.text = [[itemsArray objectAtIndex:indexPath.row] objectForKey:@"Name"];
		cell.detailTextLabel.text = [[itemsArray objectAtIndex:indexPath.row] objectForKey:@"Desc"];
	}
    return cell;
}
- (UIView*) composeViewForSelectedRow: (NSIndexPath*) indexPath cellContentFrame: (CGRect) frame {
	UIView* container = [[[UIView alloc] initWithFrame: frame] autorelease];
	NSDictionary* item = [itemsArray objectAtIndex:indexPath.row];
	NSNumber* maxSizeOfValue = [NSNumber numberWithInt:-1];
	NSNumber* labelsHeight = [NSNumber numberWithInt:-1];
	for (NSString* key in [item keyEnumerator]) {
		CGSize size = [key sizeWithFont: [UIFont fontWithName: @"Helvetica" size: 17]];
		if (size.width > [maxSizeOfValue intValue]) {
			maxSizeOfValue = [NSNumber numberWithFloat:size.width];
		}	
		labelsHeight = [NSNumber numberWithInt:size.height];
	}
	NSNumber* yIndex = [NSNumber numberWithInt:0];
	for (NSString* key in [item keyEnumerator]) {
		UILabel* keyLbl = [[UILabel alloc] initWithFrame: CGRectMake(0, 0, [maxSizeOfValue floatValue], [labelsHeight floatValue] )];
		keyLbl.text = key;
		NSString* value = [item objectForKey:key];
		
	}
	return container;
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
	
	//UITableViewCell* cell = [self.tableView cellForRowAtIndexPath:indexPath];
	//UIView* view = [[UIView alloc] initWithFrame:cell.contentView.frame];	
	selectedRow = [NSNumber numberWithInt: indexPath.row];
	[self.tableView reloadRowsAtIndexPaths:[NSArray arrayWithObject:indexPath] withRowAnimation: UITableViewScrollPositionNone];
}
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    return /*[selectedRow isEqualToNumber:[NSNumber numberWithInt:indexPath.row]] ? 90 :*/ 60;
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
    [super dealloc];
	[httpRequest release];
	[itemsArray release];
	[selectedRow release];
}


@end

