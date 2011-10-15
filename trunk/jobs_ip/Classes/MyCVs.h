#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "CXMLDocument.h"

@interface MyCVs : VCBase <UITableViewDelegate, UITableViewDataSource> {

	IBOutlet UITableView* tableView;
	CXMLDocument* doc;
}

@property(nonatomic,retain) IBOutlet UITableView* tableView;

@end
