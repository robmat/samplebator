#import <UIKit/UIKit.h>

@interface AccountsVC : UIViewController <UITableViewDelegate, UITableViewDataSource> {
	NSArray* accounts;
	IBOutlet UITableView* tableView;
}

@property(nonatomic,retain) NSArray* accounts;
@property(nonatomic,retain) IBOutlet UITableView* tableView;

@end

@interface AccountTVC : UITableViewCell {
	IBOutlet UILabel* titleLbl;
}

@property (nonatomic,retain) IBOutlet UILabel* titleLbl;

- (void)delAction: (id) sender;
- (void)goAction: (id) sender;

@end