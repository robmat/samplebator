#import "TableViewController.h"
#import "TableViewControllerWrapper.h"
#import "PathwayCell.h"
#import "DisclaimerPageViewController.h"

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
    //static NSString *CellIdentifier = @"PathwayCell";
    
    PathwayCell *cell = nil;//(PathwayCell*) [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
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
	addressStr = [addressStr stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
	
	int moveFactor = 11;
	if (website != nil && ![website isEqualToString:@""]) {
		[cell.urlBtn setTitle:website forState: UIControlStateNormal];
		[self clipButtonToItsTitleWidth:cell.urlBtn];	
	} else {
		[self moveDownView:cell.label byPixels:[NSNumber numberWithInt:moveFactor]];
		[self moveDownView:cell.detailLabel byPixels:[NSNumber numberWithInt:moveFactor]];
		[self moveDownView:cell.phoneBtn byPixels:[NSNumber numberWithInt:moveFactor]];
	}
	if (phone != nil && ![phone isEqualToString:@""]) {
		[cell.phoneBtn setTitle:phone forState: UIControlStateNormal];
		[self clipButtonToItsTitleWidth:cell.phoneBtn];	
	} else {
		[self moveDownView:cell.label byPixels:[NSNumber numberWithInt:moveFactor]];
		[self moveDownView:cell.detailLabel byPixels:[NSNumber numberWithInt:moveFactor]];
	}
	if (addressStr != nil && ![addressStr isEqualToString:@""]) {
		cell.detailLabel.text = addressStr;
	} else {
		[self moveDownView:cell.label byPixels:[NSNumber numberWithInt:moveFactor]];
	}	
	
	NSArray* arr = [dataDict objectForKey:@"Children"];
	if (arr != nil && [arr isKindOfClass:[NSArray class]] && [arr count] > 1) {
		cell.accessoryType = UITableViewCellAccessoryDisclosureIndicator;
	} else {
		cell.accessoryType = UITableViewCellAccessoryNone;
	}
	cell.navController = self.navController;
	if ([cell.label.text isEqualToString:@"QUICK PAGES"]) {
		cell.background.image = [UIImage imageNamed:@"cell_back_violet.png"];
	}
	if ([cell.label.text isEqualToString:@"Links"]) {
		cell.background.image = [UIImage imageNamed:@"cell_back_brown.png"];
	}	
	return cell;
}
- (void) moveDownView: (UIView*) view byPixels: (NSNumber*) pixels {
	CGRect frame = view.frame;
	frame = CGRectMake(frame.origin.x, frame.origin.y + [pixels floatValue], frame.size.width, frame.size.height);
	view.frame = frame;
}
- (void) clipButtonToItsTitleWidth: (UIButton*) btn {
	UILabel* lbl = btn.titleLabel;
	float width = [lbl.text sizeWithFont:lbl.font].width;
	btn.frame = CGRectMake(btn.frame.origin.x, btn.frame.origin.y, width + 10, btn.frame.size.height);
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	PathwayCell* cell = (PathwayCell*) [self.tableView cellForRowAtIndexPath:indexPath];
	if ([cell.label.text isEqualToString:@"Smart GP Disclaimer"]) {
		DisclaimerPageViewController* cpvc = [[DisclaimerPageViewController alloc] initWithNibName:nil bundle:nil];
		[self.navController pushViewController:cpvc animated:YES];
		[cpvc release];
	}
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

