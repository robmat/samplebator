#import <UIKit/UIKit.h>
#import "VCBase.h"

@interface SavedSearchVC : VCBase <UISearchBarDelegate> {
	IBOutlet UIButton* cancelBtn;
	IBOutlet UISearchBar* keywordSearchBar;
	IBOutlet UISearchBar* locationSearchBar;
	IBOutlet UITableView* tableView;
}

@property (nonatomic, retain) IBOutlet UIButton* cancelBtn;
@property (nonatomic, retain) IBOutlet UISearchBar* keywordSearchBar;
@property (nonatomic, retain) IBOutlet UISearchBar* locationSearchBar;
@property (nonatomic, retain) IBOutlet UITableView* tableView;

- (void)cancelAction: (id) sender;

@end
