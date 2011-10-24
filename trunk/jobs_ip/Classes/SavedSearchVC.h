#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "ASIHTTPRequestDelegate.h"
#import "CXMLDocument.h"

@interface SavedSearchVC : VCBase <UISearchBarDelegate, ASIHTTPRequestDelegate, UITableViewDataSource, UITableViewDelegate> {
	IBOutlet UISearchBar* keywordSearchBar;
	IBOutlet UISearchBar* locationSearchBar;
	IBOutlet UITableView* tableView;
	CXMLDocument* savedSearchesDoc;
}

@property (nonatomic, retain) IBOutlet UISearchBar* keywordSearchBar;
@property (nonatomic, retain) IBOutlet UISearchBar* locationSearchBar;
@property (nonatomic, retain) IBOutlet UITableView* tableView;

- (void)cancelAction: (id) sender;

@end
