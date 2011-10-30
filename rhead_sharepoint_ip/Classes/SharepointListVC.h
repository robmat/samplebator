#import <UIKit/UIKit.h>
#import "SharepointListTVC.h"
#import "VCBase.h"

@interface SharepointListVC : VCBase {
	
	IBOutlet UITableView* tableView;
	SharepointListTVC* sltvc;
	NSMutableDictionary* listsData;
	NSMutableDictionary* datesData;
}

@property (nonatomic,retain) IBOutlet UITableView* tableView;
@property (nonatomic,retain) SharepointListTVC* sltvc;
@property (nonatomic,retain) NSMutableDictionary* listsData;
@property (nonatomic,retain) NSMutableDictionary* datesData;

@end
