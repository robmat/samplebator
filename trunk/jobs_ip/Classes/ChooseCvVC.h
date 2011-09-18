#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "CXMLDocument.h"
#import "ASIHTTPRequestDelegate.h"

@interface ChooseCvTVC : UITableViewController <ASIHTTPRequestDelegate> {
	
	UINavigationController* navCntrl;
	CXMLDocument* doc;
	
}

@property (nonatomic, retain) UINavigationController* navCntrl;
@property (nonatomic, retain) CXMLDocument* doc;

@end

@interface ChooseCvVC : VCBase {
	
	IBOutlet UILabel* jobTitleLbl;
	ChooseCvTVC* tableVC;
	IBOutlet UITableView* tableView;
}

@property(nonatomic, retain) IBOutlet UILabel* jobTitleLbl;
@property(nonatomic, retain) IBOutlet UITableView* tableView;

@end