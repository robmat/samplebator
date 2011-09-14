#import <UIKit/UIKit.h>
#import "CXMLDocument.h"

@interface SearchResultsTVC : UITableViewController {

	UINavigationController* navCntrl;
	CXMLDocument* doc;
}

@property (nonatomic, retain) UINavigationController* navCntrl;
@property (nonatomic, retain) CXMLDocument* doc;

@end
