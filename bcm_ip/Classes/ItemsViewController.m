//
//  ProcessesViewController.m
//  bcm_ip
//
//  Created by User on 3/10/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "ItemsViewController.h"
#import "HttpRequestWrapper.h"
#import "TBXML.h"
#import "bcm_ipAppDelegate.h"

@implementation ItemsViewController

@synthesize requestParams, xmlItemName, delegate, dictionary, toolbarOutlet, tableViewOutlet;

#pragma mark -
#pragma mark View lifecycle

- (void) setAccessoryType: (UITableViewCellAccessoryType) type {
	accessory = type;
}
- (void)viewDidLoad {
	BOOL ipad = UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPad;
	frameWidth = ipad ? 302 : 735;
    [super viewDidLoad];
	self.tableView = tableViewOutlet;
	httpRequest = [[HttpRequestWrapper alloc] initWithDelegate:self];
	[httpRequest makeRequestWithParams: requestParams];
	selectedRow = -1;
    // Uncomment the following line to display an Edit button in the navigation bar for this view controller.
    self.navigationItem.rightBarButtonItem = [[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"refreshBtnLbl", nil) style:UIBarButtonItemStylePlain target:self action: @selector(refreshAction)];
	
	UIBarButtonItem *refreshBtn = [[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemRefresh
																				 target:self
																				 action:@selector(refreshAction)];
	browserBtn = [[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemSearch
																target:self
																action:@selector(launchBrowser)];
	UIBarButtonItem *flexItem = [[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemFlexibleSpace
																			  target:nil
																			  action:nil];
	NSArray *items = [NSArray arrayWithObjects: refreshBtn, flexItem, browserBtn, nil];
	[browserBtn setEnabled:NO];
	[refreshBtn release];
	[browserBtn release];
	[flexItem release];
	[toolbarOutlet setItems:items animated:NO];
}
- (void) launchBrowser {
	NSDictionary* item = [itemsArray objectAtIndex:selectedRow];
	for (NSString* key in [item keyEnumerator]) {
		NSString* value = [item objectForKey:key];
		if ([NSURL URLWithString:value] && ([value rangeOfString:@"http"].location == 0 || [value rangeOfString:@"www"].location == 0)) {
			if (!([value rangeOfString:@"http://"].location == 0)) {
				value = [NSString stringWithFormat: @"%@%@", @"http://", value];
			}
			[[UIApplication sharedApplication] openURL:[NSURL URLWithString:value]];
		}	
	}
}
- (void) refreshAction {
	[httpRequest release];
	httpRequest = [[HttpRequestWrapper alloc] initWithDelegate:self];
	[httpRequest makeRequestWithParams: requestParams];
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	NSString* responseString = [request responseString];
	TBXML* xmlDoc = [TBXML tbxmlWithXMLString:responseString];
	if (itemsArray) {
		[itemsArray release];
	}
	itemsArray = [[NSMutableArray alloc] init];
	TBXMLElement* itemElem = [TBXML childElementNamed: xmlItemName parentElement: xmlDoc.rootXMLElement]; 
	
	if (itemElem) {
		anyItemsAvailable = YES;
		do {
			TBXMLElement* itemChildElem = itemElem->firstChild;
			NSMutableDictionary* processDict = [NSMutableDictionary dictionaryWithCapacity:10];
			[itemsArray addObject:processDict];
		
			do {
				NSString* elemName = [TBXML elementName:itemChildElem];
				NSString* elemValu = [TBXML textForElement:itemChildElem];
				if ([dictionary valueByDictionary:elemName andKey:elemValu]) {
					[processDict setObject:[dictionary valueByDictionary:elemName andKey:elemValu] forKey:elemName];
				} else {
					[processDict setObject:elemValu forKey:elemName];
				}				
			} while (itemChildElem = itemChildElem->nextSibling);
		
			itemElem = [TBXML nextSiblingNamed: xmlItemName searchFromElement:itemElem];
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
    return 1;
}


- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [itemsArray count];
}

- (void)tableView:(UITableView *)tableView willDisplayCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath {
	if ([[NSNumber numberWithInt:selectedRow] isEqualToNumber:[NSNumber numberWithInt:indexPath.row]]) {
		cell.selectionStyle = UITableViewCellSelectionStyleGray;
	}
}

// Customize the appearance of table view cells.
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    static NSString *CellIdentifier = @"Cell";
    
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:CellIdentifier] autorelease];
    }
	while ([cell.contentView.subviews count] != 0 && [[cell.contentView.subviews objectAtIndex:0] tag] == 666) {
		[[cell.contentView.subviews objectAtIndex:0] removeFromSuperview];
	}
	cell.textLabel.text = @"";
	cell.detailTextLabel.text = @"";
	if (anyItemsAvailable) {	
		cell.accessoryType = accessory;
	}
    if ([[NSNumber numberWithInt:selectedRow] isEqualToNumber:[NSNumber numberWithInt:indexPath.row]]) {
		UIView* contentView = [self composeViewForSelectedRow: indexPath cellContentFrame: cell.contentView.frame];
		[cell.contentView addSubview: contentView];
		[contentView release];
	} else { //todo make it more generic, parametrize!
		cell.textLabel.text = [[itemsArray objectAtIndex:indexPath.row] objectForKey:@"Name"];
		if (![[itemsArray objectAtIndex:indexPath.row] objectForKey:@"Name"]) {
			cell.textLabel.text = [[itemsArray objectAtIndex:indexPath.row] objectForKey:@"IncidentTime"];
			if (![[itemsArray objectAtIndex:indexPath.row] objectForKey:@"IncidentTime"]) {
				cell.textLabel.text = [[itemsArray objectAtIndex:indexPath.row] objectForKey:@"Message"];
			}
		}
		cell.detailTextLabel.text = [[itemsArray objectAtIndex:indexPath.row] objectForKey:@"Desc"];
		if (![[itemsArray objectAtIndex:indexPath.row] objectForKey:@"Desc"]) {
			cell.detailTextLabel.text = [[itemsArray objectAtIndex:indexPath.row] objectForKey:@"CallTime"];
		}
	}
    return cell;
}
- (UIView*) composeViewForSelectedRow: (NSIndexPath*) indexPath cellContentFrame: (CGRect) frame {
	frameWidth = frame.size.width;
	UIView* container = [[UIView alloc] initWithFrame: frame];
	container.tag = 666;
	NSDictionary* item = [itemsArray objectAtIndex:indexPath.row];
	NSNumber* maxSizeOfKey = [NSNumber numberWithInt:-1];
	NSNumber* labelsHeight = [NSNumber numberWithInt:-1];
	for (NSString* key in [item keyEnumerator]) {
		CGSize size = [key sizeWithFont: [bcm_ipAppDelegate defaultFont]];
		if (size.width > [maxSizeOfKey intValue]) {
			maxSizeOfKey = [NSNumber numberWithFloat:size.width];
		}	
		labelsHeight = [NSNumber numberWithInt:size.height];
	}
	NSNumber* yIndex = [NSNumber numberWithInt:5];
	for (NSString* key in [item keyEnumerator]) {
		UILabel* keyLbl = [[UILabel alloc] initWithFrame: CGRectMake(5, [yIndex intValue], [maxSizeOfKey floatValue], [labelsHeight floatValue] )];
		keyLbl.text = key;
		keyLbl.font = [bcm_ipAppDelegate defaultFont];
		[container addSubview:keyLbl];
		[keyLbl release];
		
		NSString* value = [item objectForKey:key];
		float width = [value sizeWithFont: [bcm_ipAppDelegate defaultFont]].width;
		float rows = width / (frame.size.width - [maxSizeOfKey floatValue] - 20);
		float lblHeight = rows > 1 ? [labelsHeight floatValue] * 2 : [labelsHeight floatValue];
		lblHeight = rows > 2 ? [labelsHeight floatValue] * 3 : lblHeight;
		UILabel* valLbl = [[UILabel alloc] initWithFrame: CGRectMake([maxSizeOfKey floatValue] + 10, [yIndex intValue], frame.size.width - 20 - [maxSizeOfKey floatValue], lblHeight)];
		valLbl.text = value;
		valLbl.font = [bcm_ipAppDelegate defaultFont];
		if (rows > 1) {
			valLbl.numberOfLines = 2;
			
		}
		if (rows > 2) {
			valLbl.numberOfLines = 3;
		}
		[container addSubview:valLbl];
		[valLbl release];
		
		yIndex = [NSNumber numberWithInt: [yIndex intValue] + lblHeight ];
	}
	return container;
}
- (void)tableView:(UITableView *)tableView accessoryButtonTappedForRowWithIndexPath:(NSIndexPath *)indexPath {
	if ([delegate respondsToSelector: @selector(detailClicked:itemsArray:)] && anyItemsAvailable) {
		NSDictionary* item = [itemsArray objectAtIndex:indexPath.row];
		NSString* idStr = [item objectForKey:@"Id"]; 
		[delegate detailClicked: idStr itemsArray: itemsArray];
	}
}

#pragma mark -
#pragma mark Table view delegate

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	if (anyItemsAvailable) {
		[browserBtn setEnabled:NO];
		int rowTemp = selectedRow;
		selectedRow = [[NSNumber numberWithInt: indexPath.row] isEqualToNumber:[NSNumber numberWithInt:selectedRow]] ? -1 : indexPath.row;
		if (rowTemp > -1) {
			[self.tableView reloadRowsAtIndexPaths:[NSArray arrayWithObject:[NSIndexPath indexPathForRow:rowTemp inSection:0]] withRowAnimation: UITableViewScrollPositionNone];
		}
		[self.tableView reloadRowsAtIndexPaths:[NSArray arrayWithObject:indexPath] withRowAnimation: UITableViewScrollPositionNone];
		if (selectedRow > -1) {
			NSDictionary* item = [itemsArray objectAtIndex:selectedRow];
			for (NSString* key in [item keyEnumerator]) {
				NSString* value = [item objectForKey:key];
				if ([NSURL URLWithString:value] && ([value rangeOfString:@"http"].location == 0 || [value rangeOfString:@"www"].location == 0)) {
					[browserBtn setEnabled:YES];
				}	
			}
		}
	}
}
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    float height = [[NSString stringWithString:@"A"] sizeWithFont:[bcm_ipAppDelegate defaultFont]].height;
	int retVal = [[ NSNumber numberWithInt: selectedRow] isEqualToNumber:[NSNumber numberWithInt:indexPath.row]] ? [[itemsArray objectAtIndex:selectedRow] count] * (height + 2) : 60;
	if ([[ NSNumber numberWithInt: selectedRow] isEqualToNumber:[NSNumber numberWithInt:indexPath.row]]) {
		retVal = [[itemsArray objectAtIndex:selectedRow] count] == 2 ? retVal + 10 : retVal;
	}
	if (selectedRow != indexPath.row) {
		return retVal;
	}
	NSDictionary* item = [itemsArray objectAtIndex:indexPath.row];
	NSNumber* maxWidthOfKey = [NSNumber numberWithInt:-1];
	NSNumber* rowHeight = [NSNumber numberWithInt:-1];
	for (NSString* key in [item keyEnumerator]) {
		CGSize size = [key sizeWithFont: [bcm_ipAppDelegate defaultFont]];
		if (size.width > [maxWidthOfKey intValue]) {
			maxWidthOfKey = [NSNumber numberWithFloat:size.width];
		}
		rowHeight = [NSNumber numberWithFloat: size.height];
	}
	for (NSString* key in [item keyEnumerator]) {
		NSString* value = [item objectForKey:key];
		CGSize size = [value sizeWithFont: [bcm_ipAppDelegate defaultFont]];
		float width = size.width;
		float rows = width / (frameWidth - [maxWidthOfKey floatValue] - 20);
		if (rows > 1) {
			retVal += height;
		}
		if (rows > 2) {
			retVal += height;
		}
	}
	return retVal;
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
	[httpRequest release];
	[itemsArray release];
	[requestParams release];
	[xmlItemName release];
	[delegate release];
	[browserBtn release];
	[toolbarOutlet release];
	[tableViewOutlet release];
	[super dealloc];
}


@end

