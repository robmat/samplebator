
//Copyright Applicable Ltd 2011

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

	cell.data = dataDict;
	[cell initializeCell];
	
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

