#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "ASIHTTPRequest.h"

@interface AccountsVC : VCBase <UITableViewDelegate, UITableViewDataSource> {
	
	NSArray* accounts;
	IBOutlet UITableView* tableView;
	
}

@property(nonatomic,retain) NSArray* accounts;
@property(nonatomic,retain) IBOutlet UITableView* tableView;

@end

@interface AccountTVC : UITableViewCell <UIActionSheetDelegate, ASIHTTPRequestDelegate> {
	
	IBOutlet UILabel* titleLbl;
	UIViewController* delegate;
	UINavigationController* navigationController;
}

@property (nonatomic,retain) IBOutlet UILabel* titleLbl;
@property (nonatomic,retain) UIViewController* delegate;
@property (nonatomic,retain) UINavigationController* navigationController;

- (void)delAction: (id) sender;
- (void)goAction: (id) sender;
- (void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex;

@end