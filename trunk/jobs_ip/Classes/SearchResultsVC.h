#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "CXMLDocument.h"
#import "SearchResultsTVC.h"

@interface SearchResultsVC : VCBase {

	CXMLDocument* doc;
	IBOutlet UITableView* tableVew;
	SearchResultsTVC* tableViewController;
	NSString* keyword;
	NSString* location;
}

@property (nonatomic,retain) CXMLDocument* doc;
@property (nonatomic,retain) IBOutlet UITableView* tableVew;
@property (nonatomic,retain) NSString* keyword;
@property (nonatomic,retain) NSString* location;

- (void)saveSearchAction: (id) sender;
- (void)refineAction: (id) sender;

@end
