//
//  NotificationGroupsTableViewController.m
//  bcm_ip
//
//  Created by User on 3/28/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "NotificationGroupsTableViewController.h"
#import "TBXML.h"

@implementation NotificationGroupsTableViewController

@synthesize toolbarOutlet, tableViewOutlet, nnvc;

#pragma mark -
#pragma mark Initialization




#pragma mark -
#pragma mark View lifecycle


- (void)viewDidLoad {
    [super viewDidLoad];
	self.tableView = tableViewOutlet;
	httpRequest = [[HttpRequestWrapper alloc] initWithDelegate:self];
	[httpRequest makeRequestWithParams:[NSDictionary dictionaryWithObjectsAndKeys:@"getAllGroups", @"action", nil]];

}
- (void)requestFinished:(ASIHTTPRequest *)request {
	NSString* responseString = [request responseString];
	TBXML* xmlDoc = [TBXML tbxmlWithXMLString:responseString];
	if (itemsArray) {
		[itemsArray release];
	}
	itemsArray = [[NSMutableArray alloc] init];
	TBXMLElement* itemElem = [TBXML childElementNamed: @"Group" parentElement: xmlDoc.rootXMLElement]; 
	
	if (itemElem) {
		anyItemsAvailable = YES;
		do {
			TBXMLElement* itemChildElem = itemElem->firstChild;
			NSMutableDictionary* processDict = [NSMutableDictionary dictionaryWithCapacity:10];
			[itemsArray addObject:processDict];
			
			do {
				NSString* elemName = [TBXML elementName:itemChildElem];
				NSString* elemValu = [TBXML textForElement:itemChildElem];
				[processDict setObject:elemValu forKey:elemName];
			} while (itemChildElem = itemChildElem->nextSibling);
			
			itemElem = [TBXML nextSiblingNamed: @"Group" searchFromElement:itemElem];
		} while (itemElem);
	} else {
		anyItemsAvailable = NO;
		[itemsArray addObject: [NSDictionary dictionaryWithObjectsAndKeys: NSLocalizedString(@"noItemDataAvailableLbl", nil), @"Name", nil] ];
	}
	[self.tableView reloadData];
}
- (void)requestFailed:(ASIHTTPRequest *)request {
	NSError *error = [request error];
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle: NSLocalizedString(@"errorDialogTitle", nil) message: [error localizedDescription] delegate: nil cancelButtonTitle: @"OK" otherButtonTitles: nil];
	[alert show];
	[alert release];
}

#pragma mark -
#pragma mark Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    // Return the number of sections.
    return 1;
}


- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    // Return the number of rows in the section.
    return [itemsArray count];
}


// Customize the appearance of table view cells.
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    static NSString *CellIdentifier = @"Cell";
    
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:CellIdentifier] autorelease];
    }
    NSDictionary* dict = [itemsArray objectAtIndex:indexPath.row];
    cell.textLabel.text = [dict objectForKey:@"Name"];
	cell.detailTextLabel.text = [dict objectForKey:@"Desc"];
    cell.imageView.image = [UIImage imageNamed:@"tick_gray.png"];
	if ([nnvc.addressesGroupIds containsObject:[dict objectForKey:@"Id"]]) {
		cell.imageView.image = [UIImage imageNamed:@"tick.png"];
		cell.tag = 1;
	}
    return cell;
}

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
	NSDictionary* dict = [itemsArray objectAtIndex:indexPath.row];
	NSString* idStr = [dict objectForKey:@"Id"];
	UITableViewCell* cell = [self.tableView cellForRowAtIndexPath:indexPath];
	if (cell.tag == 0) {	
		cell.imageView.image = [UIImage imageNamed:@"tick.png"];
		cell.tag = 1;
		[nnvc addGroupId: idStr];
	} else {
		cell.imageView.image = [UIImage imageNamed:@"tick_gray.png"];
		cell.tag = 0;
		[nnvc delGroupId:idStr];
	}
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
	[itemsArray release];
	[httpRequest release];
	[tableViewOutlet release];
	[toolbarOutlet release];
	[nnvc release];
    [super dealloc];
}


@end

