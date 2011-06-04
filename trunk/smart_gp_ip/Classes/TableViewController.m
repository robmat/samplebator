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
	cell.phoneBtn.titleLabel.text = @"";
	cell.urlBtn.titleLabel.text = @"";
	
	NSString* address = [dataDict objectForKey:@"address"] == nil ? @"" : [dataDict objectForKey:@"address"];
	NSString* address2 = [dataDict objectForKey:@"address2"] == nil ? @"" : [dataDict objectForKey:@"address2"];
	NSString* city = [dataDict objectForKey:@"city"] == nil ? @"" : [dataDict objectForKey:@"city"];
	NSString* postcode = [dataDict objectForKey:@"postcode"] == nil ? @"" : [dataDict objectForKey:@"postcode"];
	NSString* phone = [dataDict objectForKey:@"phone"] == nil ? @"" : [dataDict objectForKey:@"phone"];
	NSString* website = [dataDict objectForKey:@"website"] == nil ? @"" : [dataDict objectForKey:@"website"];
	NSString* addressStr = [NSString stringWithFormat:@"%@ %@ %@ %@", address, address2, city, postcode];
	cell.detailLabel.text = [addressStr stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
	
	if (website != nil && ![website isEqualToString:@""]) {
		cell.urlBtn.titleLabel.text = website;
	} else {
	
	}
	if (phone != nil && ![phone isEqualToString:@""]) {
		cell.phoneBtn.titleLabel.text = phone;
	} else {
		
	}
	if ([cell.detailLabel.text isEqualToString:@""]) {
		cell.detailLabel.hidden = YES;
	} else {
		
	}	
	
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
		tableVCWrapper.title = [dataDict objectForKey:@"Title"];
		[self.navController pushViewController:tableVCWrapper animated:YES];
		[tableVCWrapper release];
	}
}
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 88.0;
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

