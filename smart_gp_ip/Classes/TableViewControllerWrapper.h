
//Copyright Applicable Ltd 2011

#import "CommonViewControllerBase.h"
#import <UIKit/UIKit.h>
#import "TableViewController.h"

@interface TableViewControllerWrapper : CommonViewControllerBase {

	IBOutlet UITableView* tableView;
	TableViewController* tableVC;
	NSArray* dataArray;
}

@property (nonatomic, retain) UITableView* tableView;
@property (nonatomic, retain) TableViewController* tableVC;
@property (nonatomic, retain) NSArray* dataArray;

@end
