#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "SharepointListsTVC.h"

@interface SharepointListsVC : VCBase {
	IBOutlet UITableView* tableView;
	SharepointListsTVC* tableVC;
	NSMutableDictionary* listsData;
}

@property (nonatomic,retain) IBOutlet UITableView* tableView;
@property (nonatomic,retain) SharepointListsTVC* tableVC;
@property (nonatomic,retain) NSMutableDictionary* listsData;

@end
