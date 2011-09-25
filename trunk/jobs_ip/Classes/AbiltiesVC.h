#import <UIKit/UIKit.h>
#import "VCBase.h"

@interface AbiltiesTVC : UITableViewController {
@public	
	UINavigationController* navCntrl;
	BOOL loggedIn;
	BOOL uploadedCV;
	BOOL recheckStatusFlag;
}

@property (nonatomic, retain) UINavigationController* navCntrl;

- (void)recheckStatus;

@end

@interface AbiltiesVC : VCBase {
@public
	IBOutlet UITableView* tableView;
	BOOL loggedIn;
	BOOL uploadedCV;
	AbiltiesTVC* atvc;
}

@property(nonatomic,retain) IBOutlet UITableView* tableView;

@end


