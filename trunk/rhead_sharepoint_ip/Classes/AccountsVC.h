#import <UIKit/UIKit.h>

@interface AccountsVC : UIViewController <UITableViewDelegate, UITableViewDataSource> {
	
	NSArray* accounts;
	IBOutlet UITableView* tableView;
	
}

@property(nonatomic,retain) NSArray* accounts;
@property(nonatomic,retain) IBOutlet UITableView* tableView;

@end

@interface AccountTVC : UITableViewCell <UIActionSheetDelegate> {
	
	IBOutlet UILabel* titleLbl;
	UIViewController* delegate;
	
}

@property (nonatomic,retain) IBOutlet UILabel* titleLbl;
@property (nonatomic,retain) UIViewController* delegate;


- (void)delAction: (id) sender;
- (void)goAction: (id) sender;
- (void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex;

@end