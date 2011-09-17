#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "CXMLDocument.h"
#import "SearchResultsTVC.h"

@interface SearchResultsVC : VCBase {

	CXMLDocument* doc;
	IBOutlet UITableView* tableVew;
	SearchResultsTVC* tableViewController;
}

@property (nonatomic,retain) CXMLDocument* doc;
@property (nonatomic,retain) IBOutlet UITableView* tableVew;

- (void)saveSearchAction: (id) sender;
- (void)refineAction: (id) sender;

@end
