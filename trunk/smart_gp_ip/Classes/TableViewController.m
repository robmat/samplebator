//
//  TableViewController.m
//  smart_gp_ip
//
//  Created by User on 5/9/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "TableViewController.h"
#import "TableViewControllerWrapper.h"
#import "PathwayCell.h"

@implementation TableViewController

@synthesize dataArray, navController;

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [dataArray count];
}

// Customize the appearance of table view cells.
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    NSDictionary* dataDict = [dataArray objectAtIndex: indexPath.row];
    static NSString *CellIdentifier = @"PathwayCell";
    
    PathwayCell *cell = (PathwayCell*) [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
		
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"PathwayCell" owner:self options:nil];
        
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (PathwayCell *) currentObject;
                break;
            }
        }
    }

	cell.label.text = [dataDict objectForKey:@"Title"];
	
	NSString* address = [dataDict objectForKey:@"address"] == nil ? @"" : [dataDict objectForKey:@"address"];
	NSString* address2 = [dataDict objectForKey:@"address2"] == nil ? @"" : [dataDict objectForKey:@"address2"];
	NSString* city = [dataDict objectForKey:@"city"] == nil ? @"" : [dataDict objectForKey:@"city"];
	NSString* phone = [dataDict objectForKey:@"phone"] == nil ? @"" : [dataDict objectForKey:@"phone"];
	NSString* postcode = [dataDict objectForKey:@"postcode"] == nil ? @"" : [dataDict objectForKey:@"postcode"];
	NSString* addressStr = [NSString stringWithFormat:@"%@ %@ %@ %@ %@", address, address2, postcode, city, phone];
	cell.detailLabel.text = addressStr;
	NSArray* arr = [dataDict objectForKey:@"Children"];
	if (arr != nil && [arr isKindOfClass:[NSArray class]] && [arr count] > 1) {
		cell.accessoryType = UITableViewCellAccessoryDisclosureIndicator;
	} else {
		cell.accessoryType = UITableViewCellAccessoryNone;
	}
	return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	NSDictionary* dataDict = [dataArray objectAtIndex: indexPath.row];
	NSArray* arr = [dataDict objectForKey:@"Children"];
	if (arr != nil && [arr isKindOfClass:[NSArray class]] && [arr count] > 1) {
		TableViewControllerWrapper* tableVCWrapper = [[TableViewControllerWrapper alloc] initWithNibName:nil bundle:nil];
		tableVCWrapper.dataArray = arr;
		[self.navController pushViewController:tableVCWrapper animated:YES];
		[tableVCWrapper release];
	}
}
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 64.0;
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)viewDidUnload {
}
- (void)dealloc {
    [super dealloc];
}


@end

