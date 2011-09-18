#import <UIKit/UIKit.h>
#import "CXMLDocument.h"
#import "ASIHTTPRequestDelegate.h"

@interface SearchResultsTVC : UITableViewController <ASIHTTPRequestDelegate> {

	UINavigationController* navCntrl;
	CXMLDocument* doc;
	int lastSelectedRow;
	
}

@property (nonatomic, retain) UINavigationController* navCntrl;
@property (nonatomic, retain) CXMLDocument* doc;

@end
