#import <UIKit/UIKit.h>

@interface PathwayCell : UITableViewCell {
	IBOutlet UILabel* label;
	IBOutlet UILabel* detailLabel;
	IBOutlet UIButton* phoneBtn;
	IBOutlet UIButton* urlBtn;
	IBOutlet UIImageView* background;
	UINavigationController* navController;
}

@property (nonatomic, retain) UILabel* label;
@property (nonatomic, retain) UILabel* detailLabel;
@property (nonatomic, retain) UIButton* phoneBtn;
@property (nonatomic, retain) UIButton* urlBtn;
@property (nonatomic, retain) UINavigationController* navController;
@property (nonatomic, retain) UIImageView* background;

- (IBAction) urlAction: (id) sender;
- (IBAction) phoneAction: (id) sender;

@end
