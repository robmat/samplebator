#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "ASIHTTPRequest.h"

@interface AccountsVC : VCBase <UITableViewDelegate, UITableViewDataSource> {
	
	NSArray* accounts;
	IBOutlet UITableView* tableView;
	IBOutlet UIImageView* blankBottomBar;
}

@property(nonatomic,retain) NSArray* accounts;
@property(nonatomic,retain) IBOutlet UITableView* tableView;
@property(nonatomic,retain) IBOutlet UIImageView* blankBottomBar;

- (void)setUpViewByOrientation: (UIInterfaceOrientation)toInterfaceOrientation;

@end

@interface AccountTVC : UITableViewCell <UIActionSheetDelegate, ASIHTTPRequestDelegate> {
	
	IBOutlet UILabel* titleLbl;
	UIViewController* delegate;
	UINavigationController* navigationController;
    IBOutlet UIButton* goBtn;
    NSString* url;
}

@property (nonatomic,retain) IBOutlet UILabel* titleLbl;
@property (nonatomic,retain) UIViewController* delegate;
@property (nonatomic,retain) UINavigationController* navigationController;
@property (nonatomic,retain) IBOutlet UIButton* goBtn;
@property (nonatomic,retain) NSString* url;

- (IBAction)delAction: (id) sender;
- (IBAction)goAction: (id) sender;
- (void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex;

@end