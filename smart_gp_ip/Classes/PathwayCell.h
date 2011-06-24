
//Copyright Applicable Ltd 2011

#import <UIKit/UIKit.h>

@interface PathwayCell : UITableViewCell {
	IBOutlet UILabel* label;
	IBOutlet UILabel* detailLabel;
	IBOutlet UIImageView* background;
	IBOutlet UIButton* detailsBtn;
	IBOutlet UIButton* websiteBtn;
	IBOutlet UIButton* phoneBtn;
	UINavigationController* navController;
	NSDictionary* data;
}

@property (nonatomic, retain) UILabel* label;
@property (nonatomic, retain) UILabel* detailLabel;
@property (nonatomic, retain) UINavigationController* navController;
@property (nonatomic, retain) UIImageView* background;
@property (nonatomic, retain) NSDictionary* data;
@property (nonatomic, retain) UIButton* detailsBtn;
@property (nonatomic, retain) UIButton* websiteBtn;
@property (nonatomic, retain) UIButton* phoneBtn;

- (IBAction) urlAction: (id) sender;
- (IBAction) phoneAction: (id) sender;
- (IBAction) detailsAction: (id) sender;
- (void) initializeCell;
- (void) moveDownView: (UIView*) view byPixels: (NSNumber*) pixels;

@end
